import { AnchorHTMLAttributes, DetailedHTMLProps } from "react";

const variants = {
    primary: "bg-black text-white hover:bg-gray-800",
    secondary: "border border-gray-200 bg-white text-gray-700 hover:border-black hover:text-black",
    tertiary: "border border-gray-300 text-gray-300 hover:border-white hover:text-white",
};

export default function Button(
    {
        children,
        variant = "primary",
        ...rest
    }: {
        children: React.ReactNode,
        variant?: keyof typeof variants,
    } & DetailedHTMLProps<AnchorHTMLAttributes<HTMLAnchorElement>, HTMLAnchorElement>
) {
    return (
        <a className={`inline-block px-4 md:px-6 py-3 font-semibold rounded-lg transition shadow-md ${variants[variant]}`} {...rest}>
            {children}
        </a>
    )
}
