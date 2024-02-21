
export const useHandleSelectOptions = () => {
    const handleSelectOptions = (object) => {
        let loadParams = [];
        for (let [name, value] of Object.entries(object)) {
            loadParams.push({
                title: value,
                value: name,
            })
        }
        return loadParams;
    }


    return {handleSelectOptions};
}
