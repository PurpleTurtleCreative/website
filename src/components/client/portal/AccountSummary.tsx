import { ChangeEventHandler, Dispatch, SetStateAction, useCallback, useMemo, useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import { formatCurrency, formatDate, formatTime, getBusinessCalendarMonth } from "@/util/formatters";
import { CURRENT_YEAR } from "@/util/constants";
import { BanknoteArrowDown, CalendarSearchIcon, DollarSign, FilterIcon, ReceiptText } from "lucide-react";
import Badge from "@/components/ui/Badge";
import MultiSelectCheckbox from "@/components/ui/MultiSelectCheckbox";

interface AccountSummaryParams {
    year: number;
    setYear: Dispatch<SetStateAction<number>>;
    data: TimesheetResponse;
}

const monthsLabelMap: Record<number, string> = {
    [0] : "1 - January",
    [1] : "2 - February",
    [2] : "3 - March",
    [3] : "4 - April",
    [4] : "5 - May",
    [5] : "6 - June",
    [6] : "7 - July",
    [7] : "8 - August",
    [8] : "9 - September",
    [9] : "10 - October",
    [10]: "11 - November",
    [11]: "12 - December",
};

export default function AccountSummary({ year, setYear, data }: AccountSummaryParams) {
    const [filterMonth, setFilterMonth] = useState(-1);
    const [filterProjects, setFilterProjects] = useState<Set<string>>(() => new Set());

    const displayRows = useMemo(() => {
        let rows = data.rows;
        if ( filterMonth >= 0 ) {
            rows = rows.filter(row => filterMonth === getBusinessCalendarMonth(row[0]));
        }
        if ( filterProjects.size > 0 ) {
            rows = rows.filter(row => filterProjects.has(row[2]));
        }
        return rows;
    }, [data.rows, filterMonth, filterProjects]);

    const availableYears = useMemo(() => {
        return Array.from(
            { length: CURRENT_YEAR - 2022 + 1 },
            (_, i) => 2022 + i
        );
    }, []);

    const availableMonths = useMemo(() => {
        return data.rows.reduce((valuesSet, row) => {
            return valuesSet.add(getBusinessCalendarMonth(row[0]));
        }, new Set([-1]));
    }, [data.rows]);

    const availableProjects = useMemo(() => {
        const projects = new Set<string>();
        for ( const row of data.rows ) {
            projects.add(row[2]);
        }
        return [ ...projects ].sort((a, b) => a.localeCompare(b));
    }, [data.rows]);

    const [
        sumCredits,
        sumDebits,
        outstandingBalance,
    ] = useMemo(
        () => {
            return displayRows.reduce(
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
        [displayRows]
    );

    const hasProjectFilter = filterProjects.size > 0;
    const hasNoResults = displayRows.length === 0;

    const handleYearChange: ChangeEventHandler<HTMLSelectElement> = useCallback(e => {
        setFilterMonth(-1);
        setYear(parseInt(e.target.value));
    }, [setYear, setFilterMonth]);

    const handleFilterMonthChange: ChangeEventHandler<HTMLSelectElement> = useCallback(e => {
        setFilterMonth(parseInt(e.target.value));
    }, [setFilterMonth]);

    const handleFilterProjectsChange = useCallback((next: Set<string>) => {
        setFilterProjects(next);
    }, []);

    const handleClearProjectFilter = useCallback(() => {
        setFilterProjects(new Set());
    }, []);

    const handleClearMonthFilter = useCallback(() => {
        setFilterMonth(-1);
    }, [setFilterMonth]);

    return (
        <div className="component-AccountSummary content-section mb-40">
            <div className="mt-4 flex items-center justify-between">
                <h1 className="text-h3">Account Summary</h1>
                <div className="flex items-center justify-between gap-3">
                    <CalendarSearchIcon className="ml-auto stroke-grey-dark" width="1.3em" height="1.3em" />
                    <select
                        value={year}
                        onChange={handleYearChange}
                        className="cursor-pointer card rounded-xl bg-off-white text-lg font-bold px-3 py-2"
                    >
                        {
                            availableYears.map(yr => {
                                return <option key={yr} value={yr}>{yr}</option>
                            })
                        }
                    </select>
                    <select
                        value={filterMonth}
                        onChange={handleFilterMonthChange}
                        className="cursor-pointer card rounded-xl bg-off-white text-lg font-bold px-3 py-2"
                    >
                        {
                            [...availableMonths].map(m => {
                                return <option key={m} value={m}>{monthsLabelMap[m] || "All months"}</option>
                            })
                        }
                    </select>
                    <FilterIcon className="ml-auto stroke-grey-dark" width="1.3em" height="1.3em" />
                    <MultiSelectCheckbox
                        options={availableProjects}
                        value={filterProjects}
                        onChange={handleFilterProjectsChange}
                        placeholder="All categories"
                        selectAllLabel="Select all"
                    />
                </div>
            </div>
            <p className="text-sm text-grey-dark">Changes may take up to 48 hours to be reflected.</p>
            <ul className="grid grid-cols-3 gap-5 mt-8 mb-12">
                <li className="card">
                    <div className="flex items-center justify-start gap-4 mb-4">
                        <Badge color="blue" icon={ReceiptText} />
                        <h2 className="text-xl">Charges</h2>
                    </div>
                    <span className="text-h3 text-blue-600 font-bold">{formatCurrency(sumDebits)}</span>
                </li>
                <li className="card">
                    <div className="flex items-center justify-start gap-4 mb-4">
                        <Badge color="emerald" icon={BanknoteArrowDown} />
                        <h2 className="text-xl">Payments</h2>
                    </div>
                    <span className="text-h3 text-emerald-600 font-bold">{formatCurrency(sumCredits * -1)}</span>
                </li>
                <li className="card">
                    <div className="flex items-center justify-start gap-4 mb-4">
                        <Badge color="orange" icon={DollarSign} />
                        <h2 className="text-xl">{filterMonth >= 0 ? "Net change" : "Balance"}</h2>
                    </div>
                    <span className="text-h3 text-orange-600 font-bold">{formatCurrency(outstandingBalance)}</span>
                </li>
            </ul>
            <div className="card p-0">
                <h2 className="text-xl p-8">Detailed Entries</h2>
                {hasNoResults ? (
                    <div className="flex flex-col items-center border-t border-t-primary-lighter px-8 py-16 text-center">
                        <p className="text-grey-dark">
                            {hasProjectFilter
                                ? "No entries match the selected categories for this time period."
                                : "No entries for this time period."}
                        </p>
                        <button
                            type="button"
                            onClick={hasProjectFilter ? handleClearProjectFilter : handleClearMonthFilter}
                            className="mt-4 cursor-pointer rounded-lg border border-primary bg-white px-4 py-2 text-sm font-bold text-primary transition-colors hover:border-primary hover:bg-primary hover:text-white"
                        >
                            {hasProjectFilter ? "Clear filters" : "Show all months"}
                        </button>
                    </div>
                ) : (
                    <div className="w-full overflow-x-auto">
                        <table className="w-full table-fixed">
                            <colgroup>
                                <col width="200" />
                                <col width="340" />
                                <col width="600" />
                                <col width="175" />
                            </colgroup>
                            <thead className="border-t border-t-primary-lighter">
                                <tr>
                                    <th scope="col">Date</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Description</th>
                                    <th scope="col" className="text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                {
                                    displayRows.map((row, index) => (
                                        <tr key={index} className={(row[6] < 0.0) ? "bg-emerald-50 text-emerald-600 font-bold" : ""}>
                                            <td className="whitespace-nowrap font-bold">
                                                {formatDate(row[0])}
                                                { (row[0].getTime() !== row[1].getTime()) && <span className="block text-sm whitespace-nowrap text-gray-500 font-normal">{`${formatTime(row[0])} – ${formatTime(row[1])}`}</span> }
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
                )}
            </div>
        </div>
    );
}
