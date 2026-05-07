import type { TimesheetResponse, TimesheetRowTuple } from "@/types/TimesheetData";
import { API_BASE_URL } from "@/util/constants";

/** Wire format: first two columns are Unix timestamps in milliseconds. */
type TimesheetApiRow = readonly [number, number, string, string, number, number, number];

interface TimesheetApiResponse {
    client: TimesheetResponse["client"];
    rows: TimesheetApiRow[];
}

function normalizeTimesheetResponse(api: TimesheetApiResponse): TimesheetResponse {
    return {
        client: api.client,
        rows: api.rows.map(
            (row): TimesheetRowTuple => [
                new Date(row[0]),
                new Date(row[1]),
                row[2],
                row[3],
                row[4],
                row[5],
                row[6],
            ]
        ),
    };
}

export function fetchTimesheetData( client: string, password: string, year: number ): Promise<TimesheetResponse> {
    return window.fetch(
        `${API_BASE_URL}/v1/timesheet?year=${year}`,
        {
            method: "GET",
            headers: {
                "Accept": "*/*",
                "Content-Type": "application/json",
                "X-PTC-Client": client,
                "X-PTC-Password": password,
            },
        }
    ).then(response => {
        if (response.ok) {
            return response.json().then((json: TimesheetApiResponse) => normalizeTimesheetResponse(json));
        } else {
            let errorMessage = response.statusText;
            if ( 401 === response.status ) {
                errorMessage = "Invalid credentials or unknown account.";
            }
            throw new Error(errorMessage);
        }
    });
}
