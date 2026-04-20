
export const dateFormatter = new Intl.DateTimeFormat('en-US', { year: "numeric", month: "short", day: "numeric" });
export const timeFormatter = new Intl.DateTimeFormat('en-US', { hour: "numeric", minute: "2-digit" });
export const usdFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2,
    signDisplay: 'negative',
    currencySign: 'accounting',
});

export const formatDate = (ms: number) => dateFormatter.format(new Date(ms));
export const formatTime = (ms: number) => timeFormatter.format(new Date(ms)).replace(' ', '').toLowerCase();
export const formatCurrency = (amount: number) => usdFormatter.format(amount);
