import React, {useEffect, useState} from 'react';


function CustomTabs({ tabs, defaultValue, onChange }) {
    const [selectedTab, setSelectedTab] = useState(defaultValue);

    const selectTab = value => {
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
