"use client";

import {
    memo,
    useCallback,
    useEffect,
    useId,
    useMemo,
    useRef,
    useState,
    type KeyboardEvent as ReactKeyboardEvent,
} from "react";
import { Check, ChevronDown, Minus } from "lucide-react";

export interface MultiSelectCheckboxProps<T extends string = string> {
    options: readonly T[];
    value: ReadonlySet<T>;
    onChange: (value: Set<T>) => void;
    placeholder?: string;
    selectAllLabel?: string;
    className?: string;
    disabled?: boolean;
    getOptionLabel?: (option: T) => string;
}

interface OptionRowProps<T extends string> {
    id: string;
    option: T;
    label: string;
    checked: boolean;
    onToggle: (option: T) => void;
}

const OptionRow = memo(function OptionRow<T extends string>({
    id,
    option,
    label,
    checked,
    onToggle,
}: OptionRowProps<T>) {
    return (
        <label
            htmlFor={id}
            className="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-black transition-colors hover:bg-primary-lightest"
        >
            <span
                className={`flex h-5 w-5 shrink-0 items-center justify-center rounded border-2 transition-colors ${
                    checked
                        ? "border-primary bg-primary text-white"
                        : "border-primary-lighter bg-white"
                }`}
                aria-hidden
            >
                {checked && <Check className="h-3.5 w-3.5 stroke-3" />}
            </span>
            <input
                id={id}
                type="checkbox"
                className="sr-only"
                checked={checked}
                onChange={() => onToggle(option)}
            />
            <span className="min-w-0 truncate">{label}</span>
        </label>
    );
}) as <T extends string>(props: OptionRowProps<T>) => React.ReactElement;

function resolveTriggerLabel<T extends string>(
    selected: ReadonlySet<T>,
    options: readonly T[],
    placeholder: string,
    getOptionLabel: (option: T) => string,
): string {
    if (selected.size === 0 || selected.size === options.length) {
        return placeholder;
    }
    if (selected.size === 1) {
        const only = [...selected][0];
        return getOptionLabel(only);
    }
    return `${selected.size} selected`;
}

export default function MultiSelectCheckbox<T extends string = string>({
    options,
    value,
    onChange,
    placeholder = "All",
    selectAllLabel = "Select all",
    className = "",
    disabled = false,
    getOptionLabel = (option: T) => option,
}: MultiSelectCheckboxProps<T>) {
    const [open, setOpen] = useState(false);
    const rootRef = useRef<HTMLDivElement>(null);
    const selectAllRef = useRef<HTMLInputElement>(null);
    const listId = useId();
    const baseId = useId();

    const selectedCount = value.size;
    const allSelected = options.length > 0 && selectedCount === options.length;
    const someSelected = selectedCount > 0 && !allSelected;

    const triggerLabel = useMemo(
        () => resolveTriggerLabel(value, options, placeholder, getOptionLabel),
        [value, options, placeholder, getOptionLabel],
    );

    const close = useCallback(() => setOpen(false), []);

    useEffect(() => {
        const input = selectAllRef.current;
        if (input) {
            input.indeterminate = someSelected;
        }
    }, [someSelected]);

    useEffect(() => {
        if (!open) {
            return;
        }
        const handlePointerDown = (event: MouseEvent) => {
            if (rootRef.current && !rootRef.current.contains(event.target as Node)) {
                close();
            }
        };
        const handleKeyDown = (event: globalThis.KeyboardEvent) => {
            if (event.key === "Escape") {
                close();
            }
        };
        document.addEventListener("mousedown", handlePointerDown);
        document.addEventListener("keydown", handleKeyDown);
        return () => {
            document.removeEventListener("mousedown", handlePointerDown);
            document.removeEventListener("keydown", handleKeyDown);
        };
    }, [open, close]);

    const toggleOpen = useCallback(() => {
        if (!disabled) {
            setOpen(prev => !prev);
        }
    }, [disabled]);

    const handleTriggerKeyDown = useCallback(
        (event: ReactKeyboardEvent<HTMLButtonElement>) => {
            if (event.key === "Enter" || event.key === " ") {
                event.preventDefault();
                toggleOpen();
            } else if (event.key === "Escape") {
                close();
            }
        },
        [toggleOpen, close],
    );

    const handleSelectAll = useCallback(() => {
        if (allSelected) {
            onChange(new Set());
        } else {
            onChange(new Set(options));
        }
    }, [allSelected, onChange, options]);

    const handleToggleOption = useCallback(
        (option: T) => {
            const next = new Set(value);
            if (next.has(option)) {
                next.delete(option);
            } else {
                next.add(option);
            }
            onChange(next);
        },
        [value, onChange],
    );

    const selectAllRowId = `${baseId}-select-all`;

    return (
        <div
            ref={rootRef}
            className={`component-MultiSelectCheckbox relative inline-block ${className}`}
        >
            <button
                type="button"
                disabled={disabled}
                aria-haspopup="listbox"
                aria-expanded={open}
                aria-controls={listId}
                onClick={toggleOpen}
                onKeyDown={handleTriggerKeyDown}
                className="card flex w-full min-w-40 cursor-pointer items-center justify-between gap-2 rounded-xl bg-off-white px-3 py-2 text-left text-lg font-bold disabled:cursor-not-allowed disabled:opacity-60"
            >
                <span className="min-w-0 truncate">{triggerLabel}</span>
                <ChevronDown
                    className={`h-5 w-5 shrink-0 stroke-grey-dark transition-transform ${open ? "rotate-180" : ""}`}
                    aria-hidden
                />
            </button>

            {open && (
                <div
                    id={listId}
                    role="listbox"
                    aria-multiselectable="true"
                    className="absolute right-0 z-50 mt-2 min-w-full w-max max-w-[min(24rem,90vw)] overflow-hidden rounded-2xl border border-primary-lighter bg-white shadow-xl shadow-primary-dark/15"
                >
                    <div className="border-b border-primary-lighter bg-off-white px-2 py-2">
                        <label
                            htmlFor={selectAllRowId}
                            className="flex cursor-pointer items-center gap-3 rounded-lg px-3 py-2 text-sm font-bold text-primary-dark transition-colors hover:bg-white"
                        >
                            <span
                                className={`flex h-5 w-5 shrink-0 items-center justify-center rounded border-2 transition-colors ${
                                    allSelected || someSelected
                                        ? "border-primary bg-primary text-white"
                                        : "border-primary-lighter bg-white"
                                }`}
                                aria-hidden
                            >
                                {allSelected && <Check className="h-3.5 w-3.5 stroke-3" />}
                                {someSelected && <Minus className="h-3.5 w-3.5 stroke-3" />}
                            </span>
                            <input
                                id={selectAllRowId}
                                ref={selectAllRef}
                                type="checkbox"
                                className="sr-only"
                                checked={allSelected}
                                onChange={handleSelectAll}
                            />
                            <span>{allSelected ? "Deselect all" : selectAllLabel}</span>
                        </label>
                    </div>
                    <div className="max-h-64 overflow-y-auto overscroll-contain p-2">
                        {options.map(option => {
                            const optionId = `${baseId}-${option}`;
                            return (
                                <OptionRow
                                    key={option}
                                    id={optionId}
                                    option={option}
                                    label={getOptionLabel(option)}
                                    checked={value.has(option)}
                                    onToggle={handleToggleOption}
                                />
                            );
                        })}
                    </div>
                </div>
            )}
        </div>
    );
}