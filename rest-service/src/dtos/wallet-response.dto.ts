export interface WalletResponse<T = any> {
  success: boolean;
  cod_error: string;       // e.g., '00' on success
  message_error: string;   // description of error or success
  data: T;                 // any payload (object, array, etc.)
}
