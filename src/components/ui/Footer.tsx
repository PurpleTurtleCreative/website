export default function Footer() {
    return (
        <footer className="component-Footer w-full bg-primary">
            <div className="max-w-5xl w-full p-8 mx-auto text-center text-sm text-off-white">
                <p>&copy; {new Date().getFullYear()} Purple Turtle Creative, LLC. All rights reserved.</p>
            </div>
        </footer>
    );
}
