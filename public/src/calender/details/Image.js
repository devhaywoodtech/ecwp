function Image(props){   
    const { img } = props;
    if(img !== undefined){
        return <img src={img} />
    }
    else{
        return "";
    }

}
export default Image;