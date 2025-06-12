import { getSoapClient } from '../soap/soapClient';
import { WalletResponse } from '../dtos/wallet-response.dto';

import { RegisterClientDto } from '../dtos/register-client.dto';
import { LoadWalletDto } from '../dtos/load-wallet.dto';
import { MakePurchaseDto } from '../dtos/make-purchase.dto';
import { ConfirmPaymentDto } from '../dtos/confirm-payment.dto';
import { CheckBalanceDto } from '../dtos/check-balance.dto';

export class WalletService {
  async registerClient(dto: RegisterClientDto): Promise<WalletResponse> {
    const client = await getSoapClient();
    const [response] = await client.registerClientAsync(dto);
    return response.return;
  }

  async loadWallet(dto: LoadWalletDto): Promise<WalletResponse> {
    const client = await getSoapClient();
    const [response] = await client.loadWalletAsync(dto);
    return response.return;
  }

  async makePurchase(dto: MakePurchaseDto): Promise<WalletResponse> {
    const client = await getSoapClient();
    const [response] = await client.makePurchaseAsync(dto);
    return response.return;
  }

  async confirmPayment(dto: ConfirmPaymentDto): Promise<WalletResponse> {
    const client = await getSoapClient();
    const [response] = await client.confirmPaymentAsync(dto);
    return response.return;
  }

  async checkBalance(dto: CheckBalanceDto): Promise<WalletResponse> {
    const client = await getSoapClient();
    const [response] = await client.checkBalanceAsync(dto);
    return response.return;
  }
}
