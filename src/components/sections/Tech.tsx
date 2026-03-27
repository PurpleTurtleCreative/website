import { InfoIcon } from "lucide-react"

export default function Tech() {

    const technologies = [
        {
            title: "Frontend",
            items: [
                "React",
                "Next.js",
                "TypeScript",
                "JavaScript (ES6+)",
                "Material UI (MUI)",
                "Tailwind",
                "SASS/SCSS/Less",
                "Cypress",
            ],
        },
        {
            title: "Backend",
            items: [
                "PHP",
                "WordPress",
                "PostgreSQL",
                "MySQL",
                "Node.js",
                "Supabase",
                "Docker",
                "Cloudflare",
                "DigitalOcean",
                "Vercel",
                "WordPress VIP",
            ],
        },
    ]

    return (
        <div className="component-Tech w-full py-20">
            <div className="content-section">
                <div className="w-2/3">
                    <h2 className="text-primary/50 mb-5">Using a Modern <span className="text-primary">Tech&nbsp;Stack</span></h2>
                    <p>Ground your project on a solid foundation with seamless hand-off to your team.</p>
                    <p className="bg-grey-lightest text-primary-dark/66 text-sm px-3 py-2 rounded-lg mt-3 w-fit"><InfoIcon className="inline-block relative bottom-[0.1em]" width="1.3em" height="1.3em" />&ensp;Actual tech stack used varies by project requirements.</p>
                </div>
                <div className="grid grid-cols-1 md:grid-cols-2 gap-8 my-12">
                    {technologies.map((tech, index) => (
                        <div key={index} className="card">
                            <h3>{tech.title}</h3>
                            <ul className="list-none mt-5 text-left flex flex-wrap gap-3">
                                {tech.items.map((item, index) => (
                                    <li key={index} className="block px-3 py-1 rounded-lg bg-grey-lightest">{item}</li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    )
}
