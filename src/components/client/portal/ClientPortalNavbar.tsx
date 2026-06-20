import { LogOutIcon, MailIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";
import { ReactElement } from "react";
import { formatCurrency } from "@/util/formatters";

export interface ClientPortalNavbarProps {
    clientName: string;
    moreNavLinks: NavLink[];
    currentYearDue?: number | null;
}

export interface NavLink {
    href: string;
    icon: ReactElement;
    label: string;
}

export default function ClientPortalNavbar({ clientName = "", moreNavLinks = [], currentYearDue = null }: ClientPortalNavbarProps) {

    const hasOutstandingBalance = currentYearDue !== null && currentYearDue > 0;

    const navLinks: NavLink[] = [
        ...moreNavLinks,
        {
            href: "mailto:michelle@purpleturtlecreative.com",
            icon: <MailIcon width="1.5em" height="1.5em" />,
            label: "Send Email",
        },
        {
            href: "/",
            icon: <LogOutIcon width="1.5em" height="1.5em" />,
            label: "Logout",
        },
    ];

    return (
        <header className="component-Navbar w-full bg-white drop-shadow-md drop-shadow-primary-dark/10">
            <div className="content-section flex items-start sm:items-center justify-between gap-3 py-1">
                <Link href="/">
                    <Image
                        src="/images/purpleturtlecreative-logo-horizontal-color.svg"
                        alt="Purple Turtle Creative"
                        width={300}
                        height={92}
                        priority
                        className="w-auto h-[70px]"
                    />
                </Link>
                <div className="flex flex-wrap items-stretch justify-end gap-3">
                    {currentYearDue !== null && (
                        <div
                            className={`flex flex-row flex-nowrap items-center gap-x-2 rounded-lg border py-2 px-3 sm:py-0 sm:px-4 font-bold text-sm ${
                                hasOutstandingBalance
                                    ? "border-orange-300 bg-orange-50 text-orange-800"
                                    : "border-primary-lighter bg-off-white text-grey-dark"
                            }`}
                            aria-label="Current amount due"
                        >
                            <span>Current due</span>
                            <span className={`${hasOutstandingBalance ? "text-orange-600" : "text-black"} text-lg`}>
                                {formatCurrency(currentYearDue)}
                            </span>
                        </div>
                    )}
                    <nav>
                        <ul className="flex flex-wrap items-center justify-end gap-1 sm:gap-3">
                            {navLinks.map((link) => (
                                <li key={link.label}>
                                    <Link href={link.href} className="button button--primary text-sm">
                                        {link.icon}<span>{link.label}</span>
                                    </Link>
                                </li>
                            ))}
                            <li className="font-bold">{clientName}</li>
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
    );
}
