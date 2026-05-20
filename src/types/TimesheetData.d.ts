/**
 * Client row tuple after normalizing the API payload (see `fetchTimesheetData`).
 * Start/end are `Date` for the same instants the API sent as Unix ms; calendar
 * fields for display and filtering use `BUSINESS_TIME_ZONE` via formatters.
 *
 * Example (conceptually from API ms values):
 *
 *     [
 *       Date,
 *       Date,
 *       "TBred Sonny's Transactions",
 *       "(12-28) Code review Keap order item and invoice data service queries",
 *       0.75,
 *       75,
 *       56.25,
 *     ]
 */
export type TimesheetRowTuple = readonly [
    Date, // start instant (from API unix timestamp ms)
    Date, // end instant (from API unix timestamp ms)
    string, // project title
    string, // description
    number, // hours
    number, // rate
    number, // amount
];

export interface TimesheetResponse {
    client: ClientData;
    rows: TimesheetRowTuple[];
}

interface ClientData {
    name: string; // The client's name.
    submitPaymentUrl: string; // URL for client to pay toward their balance.
}
