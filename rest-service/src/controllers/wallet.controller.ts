// src/controllers/wallet.controller.ts
import { Request, Response } from 'express';
import { ConfirmPaymentDto } from '../dtos/confirm-payment.dto';
import { LoadWalletDto } from '../dtos/load-wallet.dto';
import { MakePurchaseDto } from '../dtos/make-purchase.dto';
import { WalletResponse } from '../dtos/wallet-response.dto';
import { getSoapClient } from '../soap/soapClient';
import { CheckBalanceDto } from '../dtos/check-balance.dto';
import { RegisterClientDto } from '../dtos/register-client.dto';

export const registerClient = async (req: Request, res: Response) => {
  const dto: RegisterClientDto = req.body;
  try {
    const client = await getSoapClient();
    const [result] = await client.registerClientAsync(dto);
    const parsed: WalletResponse = JSON.parse(result.return?.$value || '{}');
    res.status(200).json(parsed);
  } catch (err) {
    console.error('SOAP call failed:', err);
    res.status(500).json(errorResponse());
  }
};


export const checkBalance = async (req: Request, res: Response) => {
  const dto: CheckBalanceDto = req.body;
  try {
    const client = await getSoapClient();
    const [result] = await client.checkBalanceAsync(dto);
    const parsed: WalletResponse = JSON.parse(result.return?.$value || '{}');
    res.status(200).json(parsed);
  } catch {
    res.status(500).json(errorResponse());
  }
};

export const loadWallet = async (req: Request, res: Response) => {
  const dto: LoadWalletDto = req.body;
  try {
    const client = await getSoapClient();
    const [result] = await client.loadWalletAsync(dto);
    const parsed: WalletResponse = JSON.parse(result.return?.$value || '{}');
    res.status(200).json(parsed);
  } catch {
    res.status(500).json(errorResponse());
  }
};


export const makePurchase = async (req: Request, res: Response) => {
  const dto: MakePurchaseDto = req.body;
  try {
    const client = await getSoapClient();
    const [result] = await client.makePurchaseAsync(dto);
    const parsed: WalletResponse = JSON.parse(result.return?.$value || '{}');
    res.status(200).json(parsed);
  } catch {
    res.status(500).json(errorResponse());
  }
};


export const confirmPayment = async (req: Request, res: Response) => {
  const dto: ConfirmPaymentDto = req.body;
  try {
    const client = await getSoapClient();
    const [result] = await client.confirmPaymentAsync(dto);
    const parsed: WalletResponse = JSON.parse(result.return?.$value || '{}');
    res.status(200).json(parsed);
  } catch {
    res.status(500).json(errorResponse());
  }
};

const errorResponse = (): WalletResponse => ({
  success: false,
  cod_error: '500',
  message_error: 'SOAP communication failed',
  data: null,
});
