
export const useHandleSelectOptions = () => {
    const handleSelectOptions = (object) => {
        let params = [];
        for (let [name, value] of Object.entries(object)) {
            params.push({
                title: value,
                value: name,
            })
        }
        return params;
    }


    return {handleSelectOptions};
}
