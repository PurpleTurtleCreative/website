/**
 * Raw sheet row — one string per column, in order. Example:
 *
 *     [
 *       "01/01/2026",
 *       "02:15 PM",
 *       "03:00 PM",
 *       "Platyform LLC",
 *       "TBred Sonny's Transactions",
 *       "(12-28) Code review Keap order item and invoice data service queries",
 *       "0.75",
 *       " $ 75.00 ",
 *       " $ 56.25 ",
 *     ]
 */
export type TimesheetRowTuple = readonly [
    string, // date (MM/DD/YYYY)
    string, // start time
    string, // end time
    string, // client
    string, // project
    string, // description
    string, // hours
    string, // rate
    string, // total
];

/** Parsed row after converting numeric columns from the tuple. */
export interface TimesheetRow {
    date: string;
    startTime: string;
    endTime: string;
    client: string;
    project: string;
    description: string;
    hours: number;
    rate: number;
    total: number;
}

export interface TimesheetResponse {
    rows: TimesheetRowTuple[];
}
