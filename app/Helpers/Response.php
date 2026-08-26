<?php

namespace App\Helpers;


class Response
{

    public const ACTIVE = 'response.active';
    public const INACTIVE = 'response.in_active';

    public const SUCCESS = 'response.success';
    public const FAILED = 'response.failed';
    public const NOT_AUTHORIZED = 'response.not_authorized';
    public const NOT_AUTHENTICATED = 'response.not_authenticated';
    public const USER_NOT_FOUND = 'response.user_not_found';
    public const NOT_VERIFIED = 'response.not_verified';
    public const NOT_ENABLED = 'response.not_enabled';

    public const REGISTER_SUCCESSFULLY = 'response.register_successfully';
    public const REGISTER_WITH_CONFIRMATION_SUCCESSFULLY = 'response.register_with_confirmation_successfully';

    public const CONFIRMATION_SUCCESSFULLY = 'response.confirmation_successfully';


    public const WRONG_PASSWORD = 'response.wrong_password';
    public const LOGIN_SUCCESSFULLY = 'response.login_successfully';
    public const LOGOUT_SUCCESSFULLY = 'response.logout_successfully';
    public const LOGIN_FAILED = 'response.login_failed';
    public const CODE_FAILED = 'response.code_failed';
    public const MUST_LOGIN = 'response.must_login';

    public const ADDED_SUCCESSFULLY = 'response.added_successfully';
    public const UPDATED_SUCCESSFULLY = 'response.updated_successfully';
    public const DELETED_SUCCESSFULLY = 'response.deleted_successfully';
    public const TRASHED_SUCCESSFULLY = 'response.trashed_successfully';
    public const RESTORED_SUCCESSFULLY = 'response.restored_successfully';
    public const NOT_ALLOWED = 'response.not_allowed';
    public const NOT_FOUND = 'response.not_found';

    public const DELETE_MESSAGE = 'response.delete_message';
    public const DELETE_SUB_MESSAGE = 'response.delete_sub_message';
    public const CONFIRM_TEXT = 'response.confirm_text';
    public const CANCEL_TEXT = 'response.cancel_text';


    public static function response($message, $data = null, $status = true)
    {
        return response()->json([
            'status' => true,
            'message' => is_array($message)?trans('response.'.$message[0]):$message,
            'data' => $data,
        ], $status?200:500);
    }


    /**
     * @param mixed $message
     * @param null $content
     * @param integer $status
     *
     * @return JsonResponse
     */
    public static function respondSuccess($message, $content = null, $status = 200)
    {
        return response()->json([
            'status' => true,
            'message' => trans($message),
            'data' => $content,
        ], $status);
    }

    /**
     * @param mixed $message
     * @param integer $status
     *
     * @return JsonResponse
     */
    public static function respondError($message, $status = 500)
    {
        return response()->json([
            'status' => false,
            'message' => trans($message),
            'data' => null,
        ], $status);
    }

}
