import { useMemo } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import { formatCurrency, formatDate, formatTime } from "@/util/formatters";

export default function AccountSummary({ data }: { data: TimesheetResponse }) {
    const [
        sumCredits,
        sumDebits,
        outstandingBalance,
    ] = useMemo(
        () => {
            return data.rows.reduce(
                ( arr, row ) => {
                    const newArr = [ ...arr ];
                    const amount = row[6];
                    if ( amount < 0.00 ) {
                        newArr[0] += amount;
                    } else {
                        newArr[1] += amount;
                    }
                    newArr[2] += amount;
                    return newArr;
                },
                [ 0.00, 0.00, 0.00 ] as number[]
            );
        },
        [ data.rows ]
    );

    return (
        <div className="component-AccountSummary content-section mb-40">
            <h1 className="text-h3 mt-4">Account Summary</h1>
            <ul className="grid grid-cols-3 gap-5 mt-8 mb-12">
                <li className="card">
                    <h2 className="text-xl">Charges</h2>
                    <span className="text-h3 font-bold">{formatCurrency(sumDebits)}</span>
                </li>
                <li className="card">
                    <h2 className="text-xl">Payments</h2>
                    <span className="text-h3 text-green-600 font-bold">{formatCurrency(sumCredits * -1)}</span>
                </li>
                <li className="card">
                    <h2 className="text-xl">Balance</h2>
                    <span className="text-h3 text-orange-600 font-bold">{formatCurrency(outstandingBalance)}</span>
                </li>
            </ul>
            <div className="card p-0 overflow-y-hidden overflow-x-scroll">
                <h2 className="text-xl p-4">Detailed Entries</h2>
                <table>
                    <thead className="border-t border-t-primary-lighter">
                        <tr>
                            <th>Date</th>
                            <th>Project</th>
                            <th>Description</th>
                            <th className="text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        {
                            data.rows.map((row, index) => (
                                <tr key={index} className={(row[6] < 0.0) ? "bg-green-50 text-green-600 font-bold" : ""}>
                                    <td className="whitespace-nowrap font-bold">
                                        {formatDate(row[0])}
                                        { (row[0] !== row[1]) && <span className="block text-sm whitespace-nowrap text-gray-500 font-normal">{`${formatTime(row[0])} – ${formatTime(row[1])}`}</span> }
                                    </td>
                                    <td className="whitespace-nowrap">{row[2]}</td>
                                    <td>{row[3] || "—"}</td>
                                    <td className="whitespace-nowrap text-right font-bold">
                                        {formatCurrency(row[6])}
                                        { (0.0 !== row[4] && 0.0 !== row[5]) && <span className="block text-sm whitespace-nowrap text-gray-500 font-normal">{`${row[4]}h × ${formatCurrency(row[5]).replace(".00", "")}`}</span> }
                                    </td>
                                </tr>
                            ))
                        }
                    </tbody>
                </table>
            </div>
        </div>
    );
}
