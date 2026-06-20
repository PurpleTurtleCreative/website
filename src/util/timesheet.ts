import type { TimesheetRowTuple } from "@/types/TimesheetData";

export function sumOutstandingBalance(rows: readonly TimesheetRowTuple[]): number {
    let total = 0.00;
    for ( const row of rows ) {
        total += row[6];
    }
    return total;
}
