import * as soap from 'soap';



export const getSoapClient = async () => {
  const wsdlUrl = process.env.SOAP_URL!;
  try {
    const client = await soap.createClientAsync(wsdlUrl);
    return client;
  } catch (error) {
    console.error('Failed to create SOAP client:', error);
    throw error;
  }
};
