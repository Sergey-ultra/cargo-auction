import React, {useEffect, useState} from 'react';
import "./index.scss";
import {Checkbox, FormControlLabel} from "@mui/material";

export default function TreeCheckbox({
                                         disabled = false,
                                         itemText = undefined,
                                         itemValue = undefined,
                                         options,
                                         value,
                                         onChange
                                     }) {
    const [checked, setChecked] = useState([]);

    const handleCheck = (event) => {
        let updatedList = [...checked];

        const value = Number(event.target.value);
        let childValues = [];
        const obj = options.find(option => option[itemValue] === value);
        if (obj) {
            childValues = obj.children.map(option => option[itemValue]);
            if (obj.hasIsoterm) {
                const isothermOption = options.find(option => option.hasOwnProperty('hasIsoterm') && !option.hasIsoterm);
                childValues.push(isothermOption[itemValue]);
            }
        }

        if (!updatedList.includes(value)) {
            updatedList.push(value);
            if (childValues.length) {
                updatedList.push(...childValues);
            }
        } else {
            updatedList = updatedList.filter(item => !childValues.includes(item) && value !== item);
        }
        onChange(updatedList);
    };

    useEffect(() => {
        setChecked([...value]);
    },[value]);

    return (
        <ul className="multiple">
            {options.map((option, index) =>
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
                            {option.children.map(child =>
                                <li className="child-item">
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
