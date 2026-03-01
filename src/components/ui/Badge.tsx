import type { LucideIcon } from "lucide-react";

const colorClasses = {
    "gray-dark": "bg-gray-300 text-gray-900 border-gray-400",
    "gray": "bg-gray-100 text-gray-800 border-gray-300",
    "violet": "bg-violet-100 text-violet-800 border-violet-200",
    "fuchsia": "bg-fuchsia-100 text-fuchsia-800 border-fuchsia-200",
    "emerald": "bg-emerald-100 text-emerald-800 border-emerald-200",
    "blue": "bg-blue-100 text-blue-800 border-blue-200",
    "amber": "bg-amber-100 text-amber-800 border-amber-200",
};

type ColorKey = keyof typeof colorClasses;

type BadgeProps = {
    color?: ColorKey,
    iconFilled?: boolean,
} & (
    {
        label: string,
        icon?: LucideIcon,
    } | {
        label?: undefined,
        icon: LucideIcon,
    }
)

export default function Badge(
    {
        label,
        color = "gray",
        icon: Icon,
        iconFilled = false,
    }: BadgeProps
) {
    return (
        "string" === typeof label ?
        (
            <p className={`component-Badge inline-flex items-center rounded-full gap-2 pl-2 pr-3 py-[0.15em] font-body text-xs border ${colorClasses[color]} whitespace-nowrap`}>
                {Icon && <Icon className={`inline-block w-[1.3em] ${iconFilled ? "fill-current stroke-width-0" : ""}`} />}
                <span className="font-semibold">{label}</span>
            </p>
        ) :
        (
            <div className={`component-Badge flex items-center justify-center rounded-xl w-[3em] h-[3em] ${colorClasses[color]}`}>
                <Icon className={`inline-block w-[1.5em] ${iconFilled ? "fill-current stroke-width-0" : ""}`} />
            </div>
        )
    );
}
