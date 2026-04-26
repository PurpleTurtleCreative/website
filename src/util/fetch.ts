import { API_BASE_URL } from "@/util/constants";

export function fetchTimesheetData( client: string, password: string, year: number ) {
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
            return response.json();
        } else {
            let errorMessage = response.statusText;
            if ( 401 === response.status ) {
                errorMessage = "Invalid credentials or unknown account.";
            }
            throw new Error(errorMessage);
        }
    });
}
