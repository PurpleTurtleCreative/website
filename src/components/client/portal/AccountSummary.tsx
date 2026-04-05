import { TimesheetResponse } from "@/types/TimesheetData";

export default function AccountSummary({ data }: { data: TimesheetResponse }) {
    return (
        <div className="component-AccountSummary">
            <h1>Account Summary</h1>
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>In</th>
                        <th>Out</th>
                        <th>Client</th>
                        <th>Project</th>
                        <th>Description</th>
                        <th>Hours</th>
                        <th>Rate</th>
                        <th>Debit/Credit</th>
                        <th>Reference</th>
                    </tr>
                </thead>
                <tbody>
                    {
                        data.rows.map((row, index) => (
                            <tr key={index}>
                                <td>{row[0]}</td>
                                <td>{row[1]}</td>
                                <td>{row[2]}</td>
                                <td>{row[3]}</td>
                                <td>{row[4]}</td>
                                <td>{row[5]}</td>
                                <td>{row[6]}</td>
                                <td>{row[7]}</td>
                                <td>{row[8]}</td>
                            </tr>
                        ))
                    }
                </tbody>
            </table>
        </div>
    );
}
