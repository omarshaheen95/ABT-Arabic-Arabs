#!/usr/bin/env bash
# Signs in on one version over real HTTP (php -S), moves the session file to
# the other version and opens a page there with the same cookies, in both
# directions; and does the same with only a student's "remember me" cookie.
#   bash session-http-check.sh <php old> <old dir> <php new> <new dir> <work dir>
# Both copies must share the database and APP_KEY. The accounts used get a
# known password for the run; their password, last_login columns and the
# login_sessions rows written are put back at the end.
set -u
php_old="$1"; old="$(cd "$2" && pwd)"; php_new="$3"; new="$(cd "$4" && pwd)"; work="$5"
mkdir -p "$work"
here="$(cd "$(dirname "$0")" && pwd)"
mysql="${MYSQL:-/c/laragon/bin/mysql/mysql-8.0.30-winx64/bin/mysql} -uroot nonarabs"
password='Upgrade-Check-2026!'

# the manager and the student of SmokeRoutesTest
manager_id=$($mysql -N -e "select id from managers where active=1 and force_password_change=0 and deleted_at is null order by (select count(*) from model_has_permissions where model_type='App\\\\Models\\\\Manager' and model_id=managers.id) desc, id limit 1")
student_id=$($mysql -N -e "select u.id from users u join teacher_students ts on ts.user_id=u.id where ts.teacher_id=(select t.id from teachers t join teacher_students x on x.teacher_id=t.id where t.school_id=71 and t.active=1 and t.approved=1 and t.force_password_change=0 and t.deleted_at is null and x.deleted_at is null group by t.id order by count(*) desc, t.id limit 1) and ts.deleted_at is null and u.active=1 and u.archived=0 and u.deleted_at is null order by u.id limit 1")
manager_email=$($mysql -N -e "select email from managers where id=$manager_id")
student_email=$($mysql -N -e "select email from users where id=$student_id")
# copies of the two rows, to put their columns back at the end
$mysql -e "drop table if exists upgrade_check_managers, upgrade_check_users; create table upgrade_check_managers as select * from managers where id=$manager_id; create table upgrade_check_users as select * from users where id=$student_id"
max_session=$($mysql -N -e "select coalesce(max(id),0) from login_sessions")
max_activity=$($mysql -N -e "select coalesce(max(id),0) from activity_log")
# the student home and login award points and streaks
max_streak=$($mysql -N -e "select coalesce(max(id),0) from user_streak_rewards")
$mysql -e "drop table if exists upgrade_check_levels; create table upgrade_check_levels as select * from user_achievement_levels where user_id=$student_id"
hash=$("$php_new" -r 'echo password_hash($argv[1], PASSWORD_BCRYPT, ["cost" => 10]);' "$password")
$mysql -e "update managers set password='$hash' where id=$manager_id; update users set password='$hash' where id=$student_id"

"$php_old" -S 127.0.0.1:8781 -t "$old/public" "$old/server.php" > "$work/old-server.log" 2>&1 &
old_pid=$!
# Laravel 13's router script serves the current directory: start it from public/
(cd "$new/public" && exec "$php_new" -S 127.0.0.1:8782 "$new/vendor/laravel/framework/src/Illuminate/Foundation/resources/server.php") > "$work/new-server.log" 2>&1 &
new_pid=$!
sleep 3

port() { [ "$1" = old ] && echo 8781 || echo 8782; }
dir() { [ "$1" = old ] && echo "$old" || echo "$new"; }
# GET <side> <jar> <path>: prints "status location"
get() { curl -s -o "$work/body.html" -w '%{http_code} %{redirect_url}' -b "$2" -c "$2" "http://127.0.0.1:$(port "$1")$3"; }
login() { # login <side> <jar> <login path> <email> [remember]
    rm -f "$2"
    curl -s -o "$work/form.html" -b "$2" -c "$2" "http://127.0.0.1:$(port "$1")$3" > /dev/null
    token=$(grep -o 'name="_token" value="[^"]*"' "$work/form.html" | head -1 | sed 's/.*value="//; s/"$//')
    curl -s -o /dev/null -w '%{http_code} %{redirect_url}' -b "$2" -c "$2" \
        --data-urlencode "_token=$token" --data-urlencode "email=$4" --data-urlencode "password=$password" ${5:+--data-urlencode "remember=on"} \
        "http://127.0.0.1:$(port "$1")$3"
}
session_id() { # the session id inside the encrypted cookie, read by the side that wrote it
    "$php_new" "$here/cookie-check.php" "$new" session-id "$(grep -E 'laravel_session' "$1" | awk '{print $7}')"
}
move_session() { # move_session <from> <to> <jar>
    id=$(session_id "$3")
    cp "$(dir "$1")/storage/framework/sessions/$id" "$(dir "$2")/storage/framework/sessions/$id"
    echo "session $id copied from $1 to $2"
}

for from in old new; do
    to=$([ "$from" = old ] && echo new || echo old)
    jar="$work/manager-$from.jar"
    echo "[manager] login on $from: $(login "$from" "$jar" /manager/login "$manager_email")"
    echo "[manager] home on $from: $(get "$from" "$jar" /manager/home)"
    move_session "$from" "$to" "$jar"
    echo "[manager] home on $to with the same cookies: $(get "$to" "$jar" /manager/home)"
    echo "[manager] settings on $to: $(get "$to" "$jar" /manager/settings)"

    jar="$work/student-$from.jar"
    echo "[student] login on $from: $(login "$from" "$jar" /login "$student_email")"
    echo "[student] home on $from: $(get "$from" "$jar" /home)"
    move_session "$from" "$to" "$jar"
    echo "[student] home on $to with the same cookies: $(get "$to" "$jar" /home)"

    jar="$work/remember-$from.jar"
    echo "[student] login with remember me on $from: $(login "$from" "$jar" /login "$student_email" 1)"
    grep -v laravel_session "$jar" > "$jar.only-remember"
    echo "[student] home on $to with only the remember me cookie: $(get "$to" "$jar.only-remember" /home)"
done

kill $old_pid $new_pid 2>/dev/null
wait $old_pid $new_pid 2>/dev/null

# put the accounts and the log tables back
set_cols="t.password = b.password, t.last_login = b.last_login, t.last_login_info = b.last_login_info, t.remember_token = b.remember_token, t.local = b.local, t.updated_at = b.updated_at"
$mysql -e "update managers t join upgrade_check_managers b on b.id = t.id set $set_cols; update users t join upgrade_check_users b on b.id = t.id set $set_cols; drop table upgrade_check_managers, upgrade_check_users"
$mysql -e "delete from login_sessions where id > $max_session; delete from activity_log where id > $max_activity; delete from user_streak_rewards where id > $max_streak"
$mysql -e "delete from user_achievement_levels where user_id=$student_id; insert into user_achievement_levels select * from upgrade_check_levels; drop table upgrade_check_levels"
echo "accounts restored, login_sessions rows after $max_session and activity_log rows after $max_activity deleted"
