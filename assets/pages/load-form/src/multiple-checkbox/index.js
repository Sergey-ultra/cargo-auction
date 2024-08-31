import React, {useEffect, useState} from "react";
import {Checkbox, FormControlLabel} from "@mui/material";
import "./index.scss";

export default function Index({
                                             disabled = false,
                                             itemText = undefined,
                                             itemValue = undefined,
                                             options,
                                             value,
                                             onChange
                                         }) {
    const [checked, setChecked] = useState([]);

    const handleCheck = (event) => {
        const value = Number(event.target.value);
        let updatedList = [...checked];

        if (!updatedList.includes(value)) {
            updatedList.push(value);
        } else {
            updatedList.splice(checked.indexOf(value), 1);
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
                </li>
            )}
        </ul>
    );
}
