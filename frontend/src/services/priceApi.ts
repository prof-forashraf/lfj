// src/services/priceApi.ts

import apiClient from "@/lib/apiClient";

const TROY_OUNCE_IN_GRAMS = 31.1035;

interface LiveMetalPriceResponse {
  rates: {
    XAU?: number;
    XAG?: number;
  };
}

/**
 * Fetches the live gold price per gram from the Laravel backend.
 * The backend already standardizes the price to 24K.
 */
export const fetchLiveGoldPricePerGram = async (currency: string): Promise<number> => {
  const response = await apiClient.get<LiveMetalPriceResponse>(`/tools/gold-price/${currency}`);
  const data = response.data;

  if (!data?.rates?.XAU) {
    throw new Error("Failed to fetch live gold price.");
  }

  const pricePerOunce = 1 / data.rates.XAU;
  return pricePerOunce / TROY_OUNCE_IN_GRAMS;
};


/**
 * Fetches the live silver price per gram from the Laravel backend.
 * The backend already standardizes the price to pure silver.
 */
export const fetchLiveSilverPricePerGram = async (currency: string): Promise<number> => {
  const response = await apiClient.get<LiveMetalPriceResponse>(`/tools/silver-price/${currency}`);
  const data = response.data;

  if (!data?.rates?.XAG) {
    throw new Error("Failed to fetch live silver price.");
  }

  const pricePerOunce = 1 / data.rates.XAG;
  return pricePerOunce / TROY_OUNCE_IN_GRAMS;
};