import React, { useEffect } from 'react';
import { __ } from '@wordpress/i18n';
import { Panel, PanelBody, SelectControl, TextControl, RadioControl,  Button, Snackbar } from '@wordpress/components';
import { format } from 'date-fns'
import { useSelector, useDispatch } from 'react-redux';
import { convertPhpToJsFormat, timezoneList } from '../utils/Helper';
import { fetchSettings, val, changeSettings, updateSettings, changeSaveText } from '../reducer/admin';
import Loader from '../calender/utils/Loader';

export default function Admin(props) { 
    const value = useSelector(val); 
    const dispatch = useDispatch();
    const { logo } = props;
    const { settings, loading, saveText, pages } = value;
    let data = settings;
    /***
     * Fetch Events from the API
     */
    useEffect(() => {
        dispatch(fetchSettings());       
    }, []);

    const setSettings = (name,val) => {        
        data = { ...data, [name]: val }    
        dispatch(changeSettings(data));
    }
    
    const saveSettings = () => {
        dispatch(updateSettings(settings));
        setTimeout(() => {
            dispatch(changeSaveText(false));
        }, 3000); 
    }
    
    return (
        <React.Fragment>
            <div className="ecwp-admin-header">
                <div className="ecwp-admin-container">
                    <div className="ecwp-admin-logo">
                        <img src={logo} />
                        <p>Use the shortcode <span>[wp_monthly_events]</span> on your page to display the Events Calendar.</p>
                    </div>
                </div>
            </div>            
            <div className="ecwp-admin-body">
                {
                    loading && <Loader />
                }                
                <Panel header={ __( 'Settings' , 'ecwp' ) }>
                    <React.Fragment key=".0">
                        <PanelBody title={ __( 'Date Settings of Calendar' , 'ecwp' ) }>
                            <div className='ecwp-admin-controls'>                                   
                                <RadioControl
                                    label={ __( 'Select Date Format' , 'ecwp' ) }
                                    selected={ settings?.date_format || 'Y-m-d' }
                                    options={ [
                                        { label: format(new Date(), convertPhpToJsFormat( 'F j, Y' )) + '  ' + '(F j, Y)', value: 'F j, Y' },
                                        { label: format(new Date(), convertPhpToJsFormat( 'Y-m-d' ))  + '  ' + '(Y-m-d)', value: 'Y-m-d' },
                                        { label: format(new Date(), convertPhpToJsFormat( 'm/d/Y' ))  + '  ' + '(m/d/Y)', value: 'm/d/Y' },
                                        { label: format(new Date(), convertPhpToJsFormat( 'd/m/Y' ))  + '  ' + '(d/m/Y)', value: 'd/m/Y' },
                                    ] }
                                    onChange={ ( value ) => setSettings('date_format' ,value )  }
                                />  
                                <RadioControl
                                    label={ __( 'Select Time Fomat' , 'ecwp' ) }                                    
                                    selected={ settings?.time_format || 'g:i a' }
                                    options={ [
                                        { label: format(new Date(), convertPhpToJsFormat( 'g:i a' )) + '  ' + '(g:i a)', value: 'g:i a' },
                                        { label: format(new Date(), convertPhpToJsFormat( 'g:i A' ))  + '  ' + '(g:i A)', value: 'g:i A' },
                                        { label: format(new Date(), convertPhpToJsFormat( 'H:i' ))  + '  ' + '(H:i)', value: 'H:i' },
                                    ] }
                                    onChange={ ( value ) => setSettings('time_format' ,value )  }
                                /> 
                                <SelectControl 
                                    label={ __( 'Select Time Zone' , 'ecwp' ) } 
                                    help ={ __( "When adding events to a calendar, ensure that the correct time zone is selected to ensure accurate date and time." , 'ecwp' )}                                    
                                    onChange={ ( value ) => setSettings('timezone' ,value )  }
                                    value={ settings?.timezone }
                                    >
                                    {
                                        timezoneList && timezoneList.map((optgroup, gkey) => {
                                            return (
                                                <optgroup label={optgroup.label} key={gkey}>
                                                    {
                                                        optgroup?.value.map((option,key) => {
                                                            return <option value={option?.value}>{option?.label}</option>
                                                        })
                                                    }
                                                </optgroup>
                                            )
                                        })
                                    }                                  
                                </SelectControl>                                  
                            </div>
                        </PanelBody>
                        <PanelBody title={ __( 'Display Settings of Calendar' , 'ecwp' ) }>
                            <div className='ecwp-admin-controls'>                            
                                <SelectControl 
                                    label={ __( 'Select Default View' , 'ecwp' ) } 
                                    help ={ __( "When the user visits the calendar, choose the default view for them." , 'ecwp' )}
                                    value={ settings?.default_view || 'month' }                              
                                    options={ [
                                        { label: 'Month', value: 'month' },
                                        { label: 'Day', value: 'day' },
                                        { label: 'List', value: 'list' },
                                    ] }
                                    onChange={ ( value ) => setSettings('default_view' ,value )  }
                                />
                                <RadioControl
                                    label={ __( 'Enable or Disable Search' , 'ecwp' ) }
                                    help ={ __( "Turn on or off the Search box for finding events by their name." , 'ecwp' )}
                                    selected={ settings?.search || '1' }
                                    options={ [
                                        { label: __( 'Enable', 'ecwp' ), value: '1' },
                                        { label: __( 'Disable', 'ecwp' ), value: '0' },
                                    ] }
                                    onChange={ ( value ) => setSettings('search' ,value )  }
                                />
                                <RadioControl
                                    label={ __( 'Redirect the single event template' , 'ecwp' ) }
                                    help ={ __( "When the user clicks on the Event name, direct them to the corresponding content either in a new tab or in the same tab, based on their preference." , 'ecwp' )}
                                    selected={ settings?.redirect_single || '_self' }
                                    options={ [
                                        { label: __( 'Open in same tab', 'ecwp' ), value: '_self' },
                                        { label: __( 'Open in new tab', 'ecwp' ) , value: '_blank' },
                                    ] }
                                    onChange={ ( value ) => setSettings('redirect_single' ,value )  }
                                />
                                <SelectControl 
                                    label={ __( 'Select Calender Page' , 'ecwp' ) } 
                                    help ={ __( "Select the Page in which the calendar shortcode is added. This option is mainly used on the Events Archive page to return to the main calendar page." , 'ecwp' )}
                                    value={ settings?.page || 'month' }  
                                    onChange={ ( value ) => setSettings('page' ,value )  }
                                >
                                    {
                                        pages && pages.map((page, pKey) => {
                                            return <option value={page?.ID}>{page?.post_title}</option>
                                        })
                                    }
                                </SelectControl>
                            </div>
                        </PanelBody> 
                        <PanelBody title={ __( 'URL or Slug Settings of Calendar' , 'ecwp' ) }>
                            <div className='ecwp-admin-controls'>  
                                <TextControl
                                    label={ __( 'Select Slug for Event Post types' , 'ecwp' ) } 
                                    help ={ __( "Select the URL Slug for the Event post types." , 'ecwp' )}
                                    onChange={ ( value ) => setSettings('slug_event' ,value )  }
                                    value={ settings?.slug_event || 'events' }  
                                />
                                <TextControl
                                    label={ __( 'Select Slug for Event Categories' , 'ecwp' ) } 
                                    help ={ __( "Select the URL Slug for the Event Categories." , 'ecwp' )}
                                    onChange={ ( value ) => setSettings('slug_category' ,value )  }
                                    value={ settings?.slug_category || 'events-category' }  
                                />
                                <TextControl
                                    label={ __( 'Select Slug for Event Tags' , 'ecwp' ) } 
                                    help ={ __( "Select the URL Slug for the Event Tags." , 'ecwp' )}
                                    onChange={ ( value ) => setSettings('slug_tag' ,value )  }
                                    value={ settings?.slug_tag || 'events-tag' }  
                                />
                            </div>
                        </PanelBody>
                        <div class="ecwp_save_settings">                                                     
                            <Button onClick={saveSettings} variant='primary' disabled={saveText}>{ __( 'Save Settings', 'ecwp' ) }</Button>                   
                        </div>
                    </React.Fragment>
                </Panel>
            </div>     
            {
                saveText && <Snackbar className='ecwp_snack'>{__( 'Settings saved successfully.', 'ecwp' )}</Snackbar>
            }     
        </React.Fragment>
    )
}