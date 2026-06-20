import { LogOutIcon, MailIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";
import { ReactElement } from "react";

export interface ClientPortalNavbarProps {
    clientName: string;
    moreNavLinks: NavLink[];
}

export interface NavLink {
    href: string;
    icon: ReactElement;
    label: string;
}

export default function ClientPortalNavbar({ clientName = "", moreNavLinks = [] }: ClientPortalNavbarProps) {

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
        </header>
    );
}
