import React, {ChangeEvent, useEffect, useState} from 'react';
import "./index.scss";
import {Checkbox, FormControlLabel} from "@mui/material";
import {BodyOption} from "../../LoadFormInner";

interface TreeCheckboxProps {
    options: BodyOption[],
    disabled: boolean,
    itemText: string|undefined,
    itemValue: string|undefined,
    value: number[],
    onChange: (value: number[]) => void
}

export default function TreeCheckbox({
                                         disabled = false,
                                         itemText = undefined,
                                         itemValue = undefined,
                                         options,
                                         value,
                                         onChange
                                     }: TreeCheckboxProps) {
    const [checked, setChecked] = useState<number[]>([]);

    const handleCheck = (event: ChangeEvent<HTMLInputElement>): void => {
        let updatedList: number[] = [...checked];

        const value = Number(event.target.value);
        let childValues: number[] = [];
        const obj: BodyOption|undefined = options.find((option: BodyOption): boolean => option[itemValue] === value);
        if (obj) {
            childValues = obj.children.map((option:BodyOption) => option[itemValue]);
            if (obj.hasIsoterm) {
                const isothermOption: BodyOption|undefined = options.find((option: BodyOption) => option.hasOwnProperty('hasIsoterm') && !option.hasIsoterm);
                if (isothermOption) {
                    childValues.push(isothermOption[itemValue]);
                }
            }
        }

        if (!updatedList.includes(value)) {
            updatedList.push(value);
            if (childValues.length) {
                updatedList.push(...childValues);
            }
        } else {
            updatedList = updatedList.filter((item: number): boolean => !childValues.includes(item) && value !== item);
        }
        onChange(updatedList);
    };

    useEffect((): void => {
        setChecked([...value]);
    },[value]);

    return (
        <ul className="multiple">
            {options.map((option: BodyOption, index: number): JSX.Element =>
                <li className="multiple__item" key={index}>
                    <div className="checkbox">
                        <label className="checkbox__wrapper">
                            <input
                                type="checkbox"
                                disabled={disabled}
                                value={itemValue ? option[itemValue] : option}
                                onChange={handleCheck}
                                checked={checked.includes(itemValue ? option[itemValue] : option)}
                            />
                            <span>{itemText ? option[itemText] : option}</span>
                        </label>
                    </div>
                    {option.children.length > 0 &&
                        <ul className="child-list">
                            {option.children.map((child: BodyOption, childIndex: number): JSX.Element =>
                                <li className="child-item" key={childIndex}>
                                    <div className="checkbox">
                                        <label className="checkbox__wrapper">
                                            <input
                                                type="checkbox"
                                                disabled={disabled}
                                                value={itemValue ? child[itemValue] : child}
                                                onChange={handleCheck}
                                                checked={checked.includes(itemValue ? child[itemValue] : child)}
                                            />
                                            <span>{itemText ? child[itemText] : child}</span>
                                        </label>
                                    </div>
                                </li>
                            )}
                        </ul>
                    }
                </li>
            )}
        </ul>
    );
}
