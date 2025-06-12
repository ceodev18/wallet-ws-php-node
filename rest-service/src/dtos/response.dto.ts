export interface BaseResponse<T = any> {
  success: boolean;
  cod_error: string;
  message_error: string;
  data: T;
}
