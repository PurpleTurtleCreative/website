import { BlocksIcon, ChartColumnIcon, CheckIcon, CircleFadingArrowUpIcon, HexagonIcon, UnplugIcon } from "lucide-react"

export default function Services() {

    const services = [
        {
            icon: BlocksIcon,
            title: "Build",
            subtitle: "Launch your project with a platform engineered for scale.",
            description: "I translate design systems and product specifications into resilient, maintainable web platforms using WordPress or Next.js. Architecture is structured for performance, extensibility, and long-term operational clarity.",
            bullets: [
                "Component-driven frontend systems",
                "Structured CMS modeling",
                "Headless and hybrid architectures",
                "Performance-first implementation",
            ],
        },
        {
            icon: UnplugIcon,
            title: "Integrate",
            subtitle: "Streamline day-to-day operations with unified API architecture.",
            description: "Fragmented tools create friction. I design and implement integrations that synchronize your CRM, marketing stack, analytics, and internal systems into a cohesive infrastructure.",
            bullets: [
                "Custom REST API development",
                "CRM and marketing platform integrations",
                "Workflow automation",
                "Data synchronization architecture",
            ],
        },
        {
            icon: CircleFadingArrowUpIcon,
            title: "Optimize",
            subtitle: "Fine-tune your systems for peak performance.",
            description: "As platforms mature, complexity compounds. I audit and refine systems to improve speed, search visibility, and structural integrity — without introducing instability.",
            bullets: [
                "Core Web Vitals engineering",
                "Technical SEO remediation",
                "Infrastructure and database optimization",
                "Long-term architectural consultation",
            ],
        },
        {
            icon: ChartColumnIcon,
            title: "Measure",
            subtitle: "Unlock strategic insights to take your business to the next level.",
            description: "Growth requires clarity. I implement event architecture and reporting frameworks that translate user behavior into actionable insight.",
            bullets: [
                "GA4 instrumentation design",
                "Google Tag Manager configuration",
                "Custom conversion tracking",
                "Looker Studio executive dashboards",
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
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 my-12 md:mb-80">
                    {services.map((service, index) => (
                        <div key={service.title} className={`bg-white shadow-xl shadow-primary-dark/10 rounded-3xl p-8 ${( 0 === ( index + 1 ) % 2 ) ? "md:translate-y-40" : ""}`}>
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
                                        <div className="inline-block bg-primary-lightest text-primary rounded-lg p-1">
                                            <CheckIcon width={14} height={14} />
                                        </div>
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
