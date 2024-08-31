import {BodyOption, Option} from "../pages/load-form/types";

export const useHandleSelectOptions = () => {
    const handleSelectOptions = (object: Object): Option[] => {
        let params: Option[] = [];
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

export const transformToTree = (list: BodyOption[], idTitle: string, parentIdTitle: string) => {
    let map = {};
    let node: BodyOption;
    let roots: BodyOption[] = [];
    let i: number;

    for (i = 0; i < list.length; i++) {
        map[list[i][idTitle]] = i;
        list[i].children = [];
    }

    for (i = 0; i < list.length; i++) {
        node = list[i];
        if (node[parentIdTitle] !== 0) {
            list[map[node[parentIdTitle]]].children.push(node);
        } else {
            roots.push(node);
        }
    }
    return roots;
}
