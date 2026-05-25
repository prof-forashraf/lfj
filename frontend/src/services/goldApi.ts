import apiClient from "@/lib/apiClient";

// src/services/goldApi.ts

// --- Type Definitions ---
interface LivePriceApiResponse {
  rates: {
    XAU: number;
    [key: string]: number;
  };
}

export interface LivePriceData {
  price_gram_24k: number;
  price_gram_22k: number;
  price_gram_18k: number;
  price_gram_14k: number;
  price: number; // This is price per ounce of 24k
}

export interface HistoricalDataPoint {
  date: string;
  price: number;
}

interface HistoricalFetchOptions {
    currency: string;
    range?: string;
    startDate?: string;
    endDate?: string;
}

const TROY_OUNCE_IN_GRAMS = 31.1035;

/**
 * Fetches the latest live gold prices from the Laravel backend.
 */
export const fetchLiveGoldPrices = async (currency: string): Promise<LivePriceData> => {
  const response = await apiClient.get<LivePriceApiResponse>(`/tools/gold-price/${currency}`);
  const rates = response.data?.rates;

  if (!rates?.XAU) {
    throw new Error("Failed to fetch live gold prices.");
  }

  const pricePerOunce24k = 1 / rates.XAU;
  const pricePerGram24k = pricePerOunce24k / TROY_OUNCE_IN_GRAMS;

  return {
    price: pricePerOunce24k,
    price_gram_24k: pricePerGram24k,
    price_gram_22k: pricePerGram24k * 0.9167,
    price_gram_18k: pricePerGram24k * 0.75,
    price_gram_14k: pricePerGram24k * 0.5833,
  };
};

/**
 * Fetches historical gold price data for the chart.
 */
export const fetchHistoricalGoldPrices = async (options: HistoricalFetchOptions): Promise<HistoricalDataPoint[]> => {
    const { currency, range, startDate, endDate } = options;
    let url = `/tools/gold-historical/${currency}`;

    if (startDate && endDate) {
        url = `/tools/gold-historical/${currency}?start_date=${startDate}&end_date=${endDate}`;
    } else if (range) {
        url += `/${range}`;
    }

    const response = await apiClient.get<{ data: HistoricalDataPoint[] }>(url);
    return response.data.data;
};