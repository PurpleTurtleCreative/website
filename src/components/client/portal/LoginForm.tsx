"use client";

import { useState } from "react";
import { TimesheetResponse } from "@/types/TimesheetData";
import { API_BASE_URL } from "@/util/constants";

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
                throw new Error(response.statusText);
            }
        }).then((data: TimesheetResponse) => {
            setFormStatus("success");
            onSubmit(data);
        }).catch(error => {
            setFormStatus(error.message ?? "An unknown error occurred");
        });
    };

    return (
        <div className="component-LoginForm">
            <div className="content-section">
                <div className="card max-w-xl mx-auto">
                    <div className="bg-primary text-white text-center py-5">
                        <h1 className="font-heading my-5 text-shadow-lg text-shadow-primary-dark/30 text-[#9eaafb]">Log In to Your Account</h1>
                        <p className="text-lg sm:text-xl mb-10">Enter your client name and password to access your account.</p>
                    </div>
                    {
                        ( "loading" === formStatus ) ? (
                            <div className="text-center py-5 text-sm text-grey-dark">
                                <p>Loading...</p>
                            </div>
                        ) : (
                            <form onSubmit={handleSubmit}>
                                <label htmlFor="client">Client Name</label>
                                <input type="text" id="client" name="client" value={client} onChange={handleClientChange} />
                                <label htmlFor="password">Password</label>
                                <input type="password" id="password" name="password" value={password} onChange={handlePasswordChange} />
                                <button type="submit" className="button">Log In</button>
                            </form>
                        )
                    }
                    {
                        ( ! [ "idle", "success", "loading" ].includes(formStatus) ) && (
                            <div className="text-left text-sm py-2 px-3 bg-red-50 text-red-600 border border-red-200 rounded-lg">
                                <p><strong>Failed to load your account summary.</strong><br /><small>Error: {formStatus}</small></p>
                            </div>
                        )
                    }
                    <div className="text-center py-5 text-sm text-grey-dark">
                        <p>Don&apos;t have an account? <a href="mailto:michelle@purpleturtlecreative.com" className="text-primary underline">Contact us</a> to get one.</p>
                    </div>
                </div>
            </div>
        </div>
    );
}
