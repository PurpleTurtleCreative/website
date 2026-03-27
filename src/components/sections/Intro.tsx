import Image from "next/image";

export default function Intro() {
    return (
        <div className="component-Intro w-full pt-22 sm:pt-26 bg-linear-170 from-primary-light from-0% to-primary to-50% text-white text-center sm:text-left wave-trim-bottom-mask">
            <div className="content-section flex flex-col sm:flex-row items-center justify-between sm:gap-15">
                <div className="sm:w-3/5 sm:mb-20">
                    <h1 className="font-heading my-5 text-shadow-lg text-shadow-primary-dark/30 text-[#9eaafb]">Building your <span className="text-white">Digital&nbsp;Strategy</span> backbone</h1>
                    <p className="text-xl">Technology solutions that boost business efficiency, standardize processes, and offer unique user experiences.</p>
                </div>
                <div className="w-2/5 mx-auto hidden sm:block text-center">
                    <div className="inline-block relative">
                        <Image
                            src="/images/Michelle-Blanchette_casual_circle.png"
                            alt="Michelle Blanchette smiling in professional attire on a bright blue background"
                            width={445}
                            height={494}
                            priority
                            className="drop-shadow-lg drop-shadow-primary-dark/30"
                        />
                        <p className="leading-[1.125] my-5 text-sm"><strong>Michelle Blanchette</strong><br /><small>Founder, Senior Web Developer</small></p>
                    </div>
                </div>
            </div>
        </div>
    )
}
