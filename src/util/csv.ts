import type { TimesheetRowTuple } from "@/types/TimesheetData";
import { formatDate, formatTime } from "@/util/formatters";

const CSV_HEADERS = [
    "Date",
    "Start Time",
    "End Time",
    "Category",
    "Description",
    "Hours",
    "Rate",
    "Amount",
] as const;

function escapeCsvCell(value: string): string {
    if ( /[",\n\r]/.test(value) ) {
        return `"${value.replace(/"/g, "\"\"")}"`;
    }
    return value;
}

function formatRowForCsv(row: TimesheetRowTuple): string[] {
    const hasTimeRange = row[0].getTime() !== row[1].getTime();
    const hasRateDetail = 0.0 !== row[4] && 0.0 !== row[5];

    return [
        formatDate(row[0]),
        hasTimeRange ? formatTime(row[0]) : "",
        hasTimeRange ? formatTime(row[1]) : "",
        row[2],
        row[3] || "",
        hasRateDetail ? String(row[4]) : "",
        hasRateDetail ? String(row[5]) : "",
        String(row[6]),
    ];
}

export function downloadTimesheetCsv(rows: TimesheetRowTuple[], filename: string): void {
    const lines = [
        CSV_HEADERS.join(","),
        ...rows.map(row => formatRowForCsv(row).map(escapeCsvCell).join(",")),
    ];
    const blob = new Blob([lines.join("\n")], { type: "text/csv;charset=utf-8" });
    const url = URL.createObjectURL(blob);
    const anchor = document.createElement("a");
    anchor.href = url;
    anchor.download = filename;
    anchor.click();
    URL.revokeObjectURL(url);
}
