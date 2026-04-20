import { CircleDollarSignIcon, LogOutIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";

export default function ClientPortalNavbar() {

    const navLinks = [
        {
            href: "https://buy.stripe.com/7sY8wP86bbz3cWmfyG73G03",
            icon: <CircleDollarSignIcon width="1.5em" height="1.5em" />,
            label: "Submit Payment",
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
                        width={233}
                        height={54}
                        priority
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
                    </ul>
                </nav>
            </div>
        </header>
    );
}
