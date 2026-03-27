import { BlocksIcon, ChartColumnIcon, CheckIcon, CircleFadingArrowUpIcon, HexagonIcon, UnplugIcon } from "lucide-react"

export default function Services() {

    const services = [
        {
            icon: BlocksIcon,
            title: "Build",
            subtitle: "Launch your project with a platform engineered for scale.",
            description: "I translate design systems and product specifications into resilient, maintainable web applications. Architecture is structured for performance, extensibility, and long-term operational clarity.",
            bullets: [
                "Block-based WordPress websites",
                "React SPA and component development",
                "Performance-first implementation",
                "Complete setup, training, & documentation",
            ],
        },
        {
            icon: UnplugIcon,
            title: "Integrate",
            subtitle: "Streamline day-to-day operations with seamless management flows.",
            description: "Fragmented tools create friction. I design and implement integrations that synchronize your CRM, marketing stack, analytics, and internal systems into a cohesive infrastructure.",
            bullets: [
                "Custom REST API development",
                "CRM and marketing platform integrations",
                "Workflow automation",
                "Data synchronization architecture",
            ],
        },
        {
            icon: ChartColumnIcon,
            title: "Measure",
            subtitle: "Unlock strategic insights to take your business to the next level.",
            description: "Growth requires clarity. I implement event architecture and reporting frameworks that translate user behavior into actionable insight.",
            bullets: [
                "Google Analytics events and reports",
                "Google Tag Manager configuration",
                "Custom conversion tracking",
                "Looker Studio executive dashboards",
            ],
        },
        {
            icon: CircleFadingArrowUpIcon,
            title: "Optimize",
            subtitle: "Fine-tune your systems for peak performance and profitability.",
            description: "As platforms mature, complexity compounds. I audit and refine systems to improve speed, search visibility, and structural integrity.",
            bullets: [
                "A/B testing implementation",
                "Core Web Vitals & CrUX optimization",
                "Technical SEO remediation",
                "Cost reduction strategization",
            ],
        },
    ]

    return (
        <div className="component-Services w-full py-20">
            <div className="content-section">
                <div className="w-2/3">
                    <h2 className="text-primary/50 mb-5"><span className="text-primary">Services</span> for Every Stage of Growth</h2>
                    <p>From initial launch to operational maturity, I build and refine the systems that support sustainable scale.</p>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 my-12 md:mb-28">
                    {services.map((service, index) => (
                        <div key={index} className={`card ${( 0 === ( index + 1 ) % 2 ) ? "md:translate-y-40" : ""}`}>
                            <div className="inline-block relative text-primary-lightest">
                                <HexagonIcon fill="currentColor" width={64} height={64} />
                                <div className="absolute inset-4 text-primary"><service.icon width={32} height={32} /></div>
                            </div>
                            <h3 className="mt-3 mb-1">{service.title}</h3>
                            <p className="text-xl text-black/50 mb-5">{service.subtitle}</p>
                            <p>{service.description}</p>
                            <ul className="list-none mt-5 text-left">
                                {service.bullets.map((bullet, index) => (
                                    <li key={index} className="flex items-center gap-x-3 mb-3">
                                        <CheckIcon className="inline-block bg-primary-lightest text-primary rounded-lg p-1" width="1.3em" height="1.3em" />
                                        {bullet}
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    )
}
