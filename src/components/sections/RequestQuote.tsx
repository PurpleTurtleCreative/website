import { MailIcon } from "lucide-react";
import Link from "next/link";

export default function RequestQuote() {
    return (
        <div className="component-RequestQuote w-full bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center sm:text-left wave-trim-top-mask">
            <div className="content-section">
                <div className="max-w-xl mt-20 mb-40">
                    <h2 className="font-heading my-5 text-shadow-lg text-shadow-primary-dark/30 text-[#9eaafb]">Request a <span className="text-white">Quote</span></h2>
                    <p className="text-lg sm:text-xl mb-10">Tell us about your project and we&rsquo;ll get back to you soon!</p>
                    <Link href="mailto:michelle@purpleturtlecreative.com" className="button button--primary-dark w-fit text-body max-sm:mx-auto">
                        <MailIcon width="1.5em" height="1.5em" /><span>Send Email</span>
                    </Link>
                </div>
            </div>
        </div>
    )
}
