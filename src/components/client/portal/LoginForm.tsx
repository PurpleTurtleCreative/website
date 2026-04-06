"use client";

import { useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import { API_BASE_URL } from "@/util/constants";
import Link from "next/link";
import Image from "next/image";

export default function LoginForm({ onSubmit }: { onSubmit: (data: TimesheetResponse) => void }) {
    const [formStatus, setFormStatus] = useState("idle");
    const [client, setClient] = useState("");
    const [password, setPassword] = useState("");

    const handleClientChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setClient(e.target.value);
    };

    const handlePasswordChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setPassword(e.target.value);
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        setFormStatus("loading");
        window.fetch(
            `${API_BASE_URL}/v1/timesheet`,
            {
                method: "POST",
                headers: {
                    "Accept": "*/*",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ client, password }),
            }
        ).then(response => {
            if (response.ok) {
                return response.json();
            } else {
                let errorMessage = response.statusText;
                if ( 401 === response.status ) {
                    errorMessage = "Invalid credentials or unknown account.";
                }
                throw new Error(errorMessage);
            }
        }).then((data: TimesheetResponse) => {
            setFormStatus("success");
            onSubmit(data);
        }).catch(error => {
            setFormStatus(error.message ?? "An unknown error occurred");
        });
    };

    return (
        <div className="component-LoginForm content-section my-5">
            <div className="max-w-md mx-auto text-center sm:mb-[10dvh]">
                <Link href="/" className="block w-fit mx-auto">
                    <Image
                        src="/images/purpleturtlecreative-horizontal-light.svg"
                        alt="Purple Turtle Creative"
                        width={200}
                        height={41}
                        priority
                        className="drop-shadow-md drop-shadow-primary-dark/50"
                    />
                </Link>
                <div className="card my-5 text-black">
                    <div>
                        <h1 className="font-heading text-h3 my-2 text-primary">Client Portal</h1>
                        <p className="mb-10 text-grey-dark">Access your account summary, payments, and outstanding balance.</p>
                    </div>
                    <form className="flex flex-col items-stretch justify-start gap-5 text-left text-lg" onSubmit={handleSubmit}>
                        <label>
                            <span>Client Name</span>
                            <input
                                type="text"
                                name="client"
                                value={client}
                                onChange={handleClientChange}
                                disabled={"loading" === formStatus}
                                required
                            />
                        </label>
                        <label>
                            <span>Password</span>
                            <input
                                type="password"
                                name="password"
                                value={password}
                                onChange={handlePasswordChange}
                                disabled={"loading" === formStatus}
                                required
                            />
                        </label>
                        {
                            ( ! [ "idle", "success", "loading" ].includes(formStatus) ) && (
                                <div className="text-left text-sm py-2 px-3 bg-red-50 text-red-600 border border-red-200 rounded-lg">
                                    <p><strong>Failed to load your account.</strong><br /><small>Error: {formStatus}</small></p>
                                </div>
                            )
                        }
                        <button type="submit" className="button button--primary my-2 self-center">{("loading" === formStatus) ? "Loading..." : "Sign In"}</button>
                    </form>
                </div>
                <div className="text-center text-sm text-white">
                    <p>Don&apos;t know your login? <Link href="mailto:michelle@purpleturtlecreative.com" className="underline underline-offset-3">Request access</Link>.</p>
                </div>
            </div>
        </div>
    );
}
