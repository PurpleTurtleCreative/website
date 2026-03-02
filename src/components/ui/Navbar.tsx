import { MailIcon } from "lucide-react";
import Image from "next/image";
import Link from "next/link";

export default function Navbar() {

    const navLinks = [
        {
            href: "mailto:michelle@purpleturtlecreative.com",
            icon: <MailIcon width={20} height={20} />,
            label: "Send Email",
        },
    ];

    return (
        <header className="component-Navbar w-full absolute top-0 z-999">
            <div className="container max-w-content w-full p-2 md:p-4 mx-auto flex items-center justify-between">
                <div className="w-full flex items-center justify-between">
                    <Link className="flex items-center gap-x-2" href="/">
                        <Image
                            src="/images/purpleturtlecreative-logo-horizontal-light.svg"
                            alt="Purple Turtle Creative"
                            width={233}
                            height={54}
                            priority
                            className="drop-shadow drop-shadow-primary-dark/20"
                        />
                    </Link>
                    <nav>
                        <ul className="flex items-center gap-x-1">
                            {navLinks.map((link) => (
                                <li key={link.label}>
                                    <a
                                        href={link.href}
                                        className="
                                            flex
                                            flex-row
                                            flex-nowrap
                                            items-center
                                            gap-x-2
                                            rounded-md
                                            py-3
                                            px-4
                                            text-md
                                            text-white
                                            font-bold
                                            bg-primary-dark
                                            hover:bg-black
                                        "
                                    >
                                        {link.icon}<span>{link.label}</span>
                                    </a>
                                </li>
                            ))}
                        </ul>
                    </nav>
                </div>
            </div>
        </header>
    );
}
