/**
 * Refactored API row tuple, in order. Example:
 *
 *     [
 *       1767294900000,
 *       1767297600000,
 *       "TBred Sonny's Transactions",
 *       "(12-28) Code review Keap order item and invoice data service queries",
 *       0.75,
 *       75,
 *       56.25,
 *     ]
 */
export type TimesheetRowTuple = readonly [
    number, // start unix timestamp (ms)
    number, // end unix timestamp (ms)
    string, // project title
    string, // description
    number, // hours
    number, // rate
    number, // amount
];

export interface TimesheetResponse {
    rows: TimesheetRowTuple[];
}
