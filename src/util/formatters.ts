import { BUSINESS_TIME_ZONE } from "@/util/constants";

export const dateFormatter = new Intl.DateTimeFormat("en-US", {
    timeZone: BUSINESS_TIME_ZONE,
    year: "numeric",
    month: "short",
    day: "numeric",
});
export const timeFormatter = new Intl.DateTimeFormat("en-US", {
    timeZone: BUSINESS_TIME_ZONE,
    hour: "numeric",
    minute: "2-digit",
});
export const usdFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
    signDisplay: 'negative',
    currencySign: 'accounting',
});

export const formatDate = (date: Date) => dateFormatter.format(date);
export const formatTime = (date: Date) => timeFormatter.format(date).replace(" ", "").toLowerCase();

const businessMonthNumeric = new Intl.DateTimeFormat("en-US", {
    timeZone: BUSINESS_TIME_ZONE,
    month: "numeric",
});

/** 0–11 month index for the given instant in `BUSINESS_TIME_ZONE`. */
export function getBusinessCalendarMonth(date: Date): number {
    return parseInt(businessMonthNumeric.format(date), 10) - 1;
}
export const formatCurrency = (amount: number) => usdFormatter.format(amount);
