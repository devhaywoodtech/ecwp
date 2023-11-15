import * as React from 'react';
import Stack from '@mui/material/Stack';
import Button from '@mui/material/Button';
import ButtonGroup from '@mui/material/ButtonGroup';
import TextField from '@mui/material/TextField';
import FormControl from '@mui/material/FormControl';
import Input from '@mui/material/Input';
import InputLabel from '@mui/material/InputLabel';
import InputAdornment from '@mui/material/InputAdornment';
import IconButton from '@mui/material/IconButton';
import SearchIcon from '@mui/icons-material/Search';
import ClearIcon from '@mui/icons-material/Clear';
import { format, add, sub, getYear, getMonth, isSameMonth } from 'date-fns'
import CalendarViewMonthIcon from '@mui/icons-material/CalendarViewMonth';
import FormatListBulletedIcon from '@mui/icons-material/FormatListBulleted';
import ViewDayIcon from '@mui/icons-material/ViewDay';
import ChevronLeftIcon from '@mui/icons-material/ChevronLeft';
import ChevronRightIcon from '@mui/icons-material/ChevronRight';
import TodayIcon from '@mui/icons-material/Today';
import { LocalizationProvider } from '@mui/x-date-pickers/LocalizationProvider';
import { AdapterDateFns  } from '@mui/x-date-pickers/AdapterDateFns';
import { MobileDatePicker } from '@mui/x-date-pickers/MobileDatePicker';
import { __ } from '@wordpress/i18n';
import { applyFilters } from '@wordpress/hooks';
import { useDispatch } from 'react-redux';
import { nextMonth, prevMonth, setMonth, fetchEvents, searchEvents, setView, fetchLatestEvents } from './reducer/admin';


function Toolbar(props) {   
    let { current, view, latestEvent, term, tax, searchEnable } = props;   
    let year = getYear(current); 
    const [ monthYear, setMonthYear ]  = React.useState();
    const [ search, setSearch ]  = React.useState('');
    const [ searchFlag, setSearchFlag ]  = React.useState(false);
    let previousmonth = format(sub(current, { years: 0, months: 1 }), 'MMM yyyy');
    let previousmonthAbbr = format(sub(current, { years: 0, months: 1 }), 'MMMM yyyy');
    let previousmonthInt = format(sub(current, { years: 0, months: 1 }), 'L');
    let nextmonth = format(add(current, { years: 0, months: 1 }), 'MMM yyyy');
    let nextmonthAbbr = format(add(current, { years: 0, months: 1 }), 'MMMM yyyy');
    let nextmonthInt = format(add(current, { years: 0, months: 1 }), 'L');
    const dispatch = useDispatch();

    const changenextmonth = () => {
        dispatch(nextMonth()); 
        dispatch(fetchEvents( { year, mon : nextmonthInt, term, tax } ));
    }

    const changeprevmonth = () => { 
        dispatch(prevMonth());
        dispatch(fetchEvents( { year, mon : previousmonthInt, term, tax } ));
    }

    const goToLatest = () => { 
        dispatch(setMonth(latestEvent));
        setMonthYear(latestEvent)
        dispatch(fetchLatestEvents({tax, term}));
    }

    const changeselectedmonth = () => {    
        if(monthYear !== undefined){
            dispatch(setMonth(monthYear));
            let newYear = getYear(monthYear);  
            let newMonth = getMonth(monthYear); 
            dispatch(fetchEvents( { year : newYear, mon : (newMonth + 1), term, tax } ));
        }
    }

    const handleSearch = () => {
        dispatch(setView('list'));
        dispatch(searchEvents( { search } ));
        setSearchFlag(true);
    }

    const handleClear = () => {
        setSearch(''); 
        if(searchFlag){
            dispatch(searchEvents( { search : '' } ));
            setSearchFlag(false);
        }
    }

    return (
        <LocalizationProvider dateAdapter={AdapterDateFns}>
            <Stack direction={{ xs: 'column', sm: 'row' }} spacing={2} alignItems="center" justifyContent="space-between" mb={2}>
                <Stack direction="row" spacing={1} alignItems="center" flexWrap={{ xs : 'wrap' }} justifyContent={{ xs: "center" , sm : "space-between" }} columnGap={{ xs: '0', sm: '0.5' }} rowGap={{ xs:'10px' }}>
                    <Button variant='outlined' size="small" className="ecwp_month_nav" startIcon={<ChevronLeftIcon />} onClick={() => changeprevmonth()}>
                        <abbr title={previousmonthAbbr}>{previousmonth}</abbr>
                    </Button>                    
                    <MobileDatePicker                                             
                        views={['month','year']} 
                        value={current || monthYear}  
                        onClose={() => changeselectedmonth()}                        
                        onChange={(newValue) =>  setMonthYear(newValue) }
                        renderInput={(params) => <TextField {...params} className="ecwp_month_picker" style={{ maxWidth : 200 }} variant="outlined" helperText={null} />} 
                    />
                    <Button variant='outlined' size="small" className="ecwp_month_nav" endIcon={<ChevronRightIcon />} onClick={() => changenextmonth() }>
                        <abbr title={nextmonthAbbr}>{nextmonth}</abbr>
                    </Button>    
                    {
                        !isSameMonth(latestEvent,current) && 
                        <Button variant='outlined' size="small" startIcon={<TodayIcon />} onClick={() => goToLatest() }>
                            { applyFilters('mCalendar-text-latestEvent', __('Upcoming Events','ecwp')) }
                        </Button> 
                    }        
                </Stack>
                <Stack direction={{ xs: 'column', sm: 'row' }} spacing={2} alignItems="center" justifyContent="space-between">
                    {
                        searchEnable === '1' && 
                        <FormControl sx={{ m: 0, width: { xs: '100%', sm: '25ch' } }} variant="standard">
                            <InputLabel className='ecwp_search_label'>{ applyFilters('mCalendar-text-searchEvent', __('Search Events','ecwp')) }</InputLabel>
                            <Input 
                                value={search} 
                                onChange={(e) => setSearch(e.target.value)} 
                                onKeyDown={(ev) => {                                    
                                    if (ev.key === 'Enter') { 
                                      handleSearch()
                                      ev.preventDefault();
                                    }
                                }}
                                className='ecwp_search' 
                                endAdornment={
                                    <InputAdornment position="end">
                                        <IconButton size='small' onClick={handleSearch}><SearchIcon /></IconButton>
                                        {
                                            search && 
                                            <IconButton size='small' onClick={handleClear}><ClearIcon /></IconButton>
                                        }
                                    </InputAdornment>
                                } 
                            />
                        </FormControl> 
                    }                                      
                    <ButtonGroup size="small" variant="outlined" sx={{ width: { xs: '100%', sm : 'auto' } }}>
                        <Button startIcon={<CalendarViewMonthIcon />} 
                            onClick={() => dispatch(setView('month'))} 
                            color={view === 'month' ? 'secondary' : 'primary'} sx={{ width: { xs: '100%', sm : 'auto' } }}>
                                {__('Month','ecwp')}
                        </Button>
                        <Button startIcon={<ViewDayIcon />} 
                            onClick={() => dispatch(setView('day'))} 
                            color={view === 'day' ? 'secondary' : 'primary'} sx={{ width: { xs: '100%', sm : 'auto' } }}>
                                {__('Day','ecwp')}
                        </Button>
                        <Button startIcon={<FormatListBulletedIcon />} 
                            onClick={() => dispatch(setView('list'))} 
                            color={view === 'list' ? 'secondary' : 'primary'} sx={{ width: { xs: '100%', sm : 'auto' } }}>
                                {__('List','ecwp')}
                        </Button>                     
                    </ButtonGroup>
                </Stack>
            </Stack>
        </LocalizationProvider>
    )
}

export default Toolbar;
