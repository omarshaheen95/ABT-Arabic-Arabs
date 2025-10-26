//Custom Functions -------------------------------------------------------------------------------------------------------
const csrf = $('meta[name=csrf-token]').attr('content');

$(document).ready(function () {

})

function getLessonsByGrade(on_change_name = 'grade_id',callback=null) {
    if (typeof getLessonsByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"], select[name="lesson_type"]').change(function () {
            let grade = $('select[name="' + on_change_name + '"]').val();
            let lesson_type = $('select[name="lesson_type"]').val();
            //value not null and not empty
            if (grade !== null && grade !== '' && lesson_type !== null && lesson_type !== '') {
                var selectElement = $(this);
                selectLoading(selectElement,true)
                $.ajax({
                    type: "get",
                    url: getLessonsByGradeURL,
                    data: {
                        '_token': csrf,
                        'grade_id': grade,
                        'lesson_type': lesson_type
                    }

                }).done(function (data) {
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                    if ($('select[name="lesson_id"]').length) {
                        $('select[name="lesson_id"]').html(data.html);
                        $('select[name="lesson_id"]').trigger('change');

                    }
                    if ($('select[name="lesson_id[]"]').length) {
                        $('select[name="lesson_id[]"]').html(data.html);
                        $('select[name="lesson_id[]"]').trigger('change');

                    }
                    selectLoading(selectElement,false)
                });
            }
        });
    }
}

function getStoriesByGrade(on_change_name = 'grade',callback=null) {
    if (typeof getStoriesByGradeURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            if ($(this).val()){
                let value = $(this).val()
                var selectElement = $(this);
                selectLoading(selectElement,true)
                $.ajax({
                    type: "get",
                    url: getStoriesByGradeURL,
                    data: {'_token': csrf, 'grade': value}

                }).done(function (data) {
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                    if ($('select[name="story_id"]').length) {
                        $('select[name="story_id"]').html(data.html);
                        $('select[name="story_id"]').trigger('change');

                    }
                    if ($('select[name="story_id[]"]').length) {
                        $('select[name="story_id[]"]').html(data.html);
                        $('select[name="story_id[]"]').trigger('change');

                    }
                    selectLoading(selectElement,false)
                });
            }

        });
    }
}

function getTeacherBySchool(on_change_name = 'school_id',callback=null) {
    if (typeof getTeacherBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"]').change(function () {
            if ($(this).val()){
                var id = $(this).val();
                var url = getTeacherBySchoolURL;
                url = url.replace(':id', id);
                var selectElement = $(this);
                selectLoading(selectElement,true)

                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                    $('select[name="teacher_id"]').html(data.html);
                    $('select[name="teacher_id"]').trigger('change');
                    selectLoading(selectElement,false)
                });
            }

        });
    }
}

function getSectionBySchool(on_change_name = 'school_id',callback=null) {
    if (typeof getSectionBySchoolURL !== 'undefined') {
        $('select[name="' + on_change_name + '"], select[name="year_id"]').change(function () {
            var id = $('select[name="' + on_change_name + '"]').val();
            if (id){
                var url = getSectionBySchoolURL;
                var selectElement = $(this);
                selectLoading(selectElement,true)
                url = url.replace(':id', id);
                let year = $('select[name="year_id"]');
                if (typeof year !=='undefined' && year.val()){
                    url+='?year_id='+year;
                }
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                    if ($('select[name="section"]').length) {
                        $('select[name="section"]').html(data.html);
                        $('select[name="section"]').trigger('change');

                    }
                    if ($('select[name="section[]"]').length) {
                        $('select[name="section[]"]').html(data.html);
                        $('select[name="section[]"]').trigger('change');

                    }
                    selectLoading(selectElement,false)
                });
            }

        });
    }
}

function getSectionByTeacher(on_change_name = 'teacher_id',callback=null) {
    if (typeof getSectionByTeacherURL !== 'undefined') {

        $('select[name="teacher_id"], select[name="year_id"]').change(function () {
            var id = $('select[name="teacher_id"]').val();
            if (id){
                var url = getSectionByTeacherURL;
                var selectElement = $(this);
                selectLoading(selectElement,true)
                url = url.replace(':id', id);
                let year = $('select[name="year_id"]');
                if (typeof year !=='undefined' && year.val()){
                    url+='?year_id='+year;
                }
                $.ajax({
                    type: "get",
                    url: url,
                }).done(function (data) {
                    if (typeof callback === 'function') {
                        callback(true);
                    }
                    if ($('select[name="section"]').length) {
                        $('select[name="section"]').html(data.html);
                        $('select[name="section"]').trigger('change');

                    }
                    if ($('select[name="section[]"]').length) {
                        $('select[name="section[]"]').html(data.html);
                        $('select[name="section[]"]').trigger('change');

                    }
                    selectLoading(selectElement,false)
                });
            }

        });
    }
}

function callAllEvents() {
    if (typeof $('select[name="grade_id"], select[name="lesson_type"]') !=='undefined'){
        getLessonsByGrade()
    }
    if (typeof $('select[name="grade"]') !=='undefined'){
        getStoriesByGrade()
    }
    if (typeof $('select[name="school_id"]') !=='undefined'){
        getTeacherBySchool()
    }
    if (typeof $('select[name="school_id"]') !=='undefined'){
        getSectionBySchool()
    }
    if (typeof $('select[name="teacher_id"]') !=='undefined'){
        getSectionByTeacher()
    }
}
