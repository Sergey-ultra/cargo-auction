export interface Option {
    title: string,
    value: string,
}

export interface LabelOption {
    id: number,
    label: string
}

export interface LoadingType {
    name: string,
    short_name: string,
    typeId: number
}

export interface LoadingOption {
    title: string,
    value: number,
}

export interface BodyOption {
    id: number,
    name: string,
    attribs: number,
    parentId: number,
    children: BodyOption[],
    hasIsoterm: boolean,
    position: number,
    short_name: string,
    typeId: number
}

export interface Contact {
    id: number,
    email: string,
    mobilePhone: string,
    name: string,
    phone: string,
}