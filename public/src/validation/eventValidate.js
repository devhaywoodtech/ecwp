import * as yup from "yup";
const eventValidate = {
    name: yup.string().required("Event Name is required"),
};

export default eventValidate;