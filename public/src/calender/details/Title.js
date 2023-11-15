function Title(props){   
    const { title, link, color, strip, settings } = props; 
    let stripTitle = strip !== undefined ? title.substring(0, strip) + '...' : title;
    if(link !== undefined ){
        return(
            <a href={link} title={title} target={settings?.redirect_single}>
                <h2 dangerouslySetInnerHTML={{__html: stripTitle}}  style={{ color } }/>
            </a>
        )
    }
    else{
        return <p className="ecwp_event" dangerouslySetInnerHTML={{__html: stripTitle}} /> 
    }
}
export default Title;