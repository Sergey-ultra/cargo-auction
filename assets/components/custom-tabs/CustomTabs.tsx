import React, {useEffect, useState} from 'react';
import {Option} from "../../hooks/handleSelectOptions";

export interface TabProps {
    tabs: Option[],
    defaultValue: string,
    onChange: (value: string) => void
}

function CustomTabs({ tabs, defaultValue, onChange }: TabProps) {
    const [selectedTab, setSelectedTab] = useState<string>(defaultValue);

    const selectTab = (value : string): void => {
        setSelectedTab(value);
        onChange(value);
    }

    return(
    <div className="cTabs">
        {tabs.map((tab) =>
            <div
                className={`cItem ${tab.value === selectedTab ? 'cItem-active' : ''}`}
                key={tab.value}
                onClick={() => selectTab(tab.value)}>
            {tab.title}
            </div>
        )}
    </div>);
}

export default CustomTabs;
