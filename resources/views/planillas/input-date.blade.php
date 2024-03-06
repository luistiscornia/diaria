<div x-data="app()" x-init="[initDate(), getNoOfDays()]" x-cloak>
    <div class="container mx-auto px-4 py-2 md:py-1 ">
        <div class="w-40">
            <div class="relative">
                <input type="hidden" name="date" x-ref="date" :value="datepickerValue" />
                <input type="text" x-on:click="showDatepicker = !showDatepicker" x-model="datepickerValue"
                    x-on:keydown.escape="showDatepicker = false"
                    class="w-full pl-4 pr-3 py-2 leading-none rounded-lg cursor-pointer shadow-sm focus:outline-none text-secondary-100 font-medium focus:ring focus:ring-blue-600 focus:ring-opacity-50"
                    placeholder="Fecha" readonly />

                <div class="bg-white-100 mt-12 rounded-lg shadow p-4 absolute top-0 left-0" style="width: 17rem"
                    x-show.transition="showDatepicker" @click.away="showDatepicker = false">
                    <div class="flex justify-between items-center mb-2">
                        <div>
                            <span x-text="MONTH_NAMES[month]" class="text-lg font-bold text-gray-800"></span>
                            <span x-text="year" class="ml-1 text-lg text-gray-600 font-normal"></span>
                        </div>
                        <div>
                            <button type="button"
                                class="focus:outline-none focus:shadow-outline transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-100 p-1 rounded-full"
                                @click="if (month == 0) {
                                      year--;
                                      month = 12;
                                    } month--; getNoOfDays()">
                                <svg class="h-6 w-6 text-gray-400 inline-flex" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 19l-7-7 7-7" />
                                </svg>
                            </button>
                            <button type="button"
                                class="focus:outline-none focus:shadow-outline transition ease-in-out duration-100 inline-flex cursor-pointer hover:bg-gray-100 p-1 rounded-full"
                                @click="if (month == 11) {
                                      month = 0;
                                      year++;
                                    } else {
                                      month++;
                                    } getNoOfDays()">
                                <svg class="h-6 w-6 text-gray-400 inline-flex" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex flex-wrap mb-3 -mx-1">
                        <template x-for="(day, index) in DAYS" :key="index">
                            <div style="width: 14.26%" class="px-0.5">
                                <div x-text="day" class="text-gray-800 font-medium text-center text-xs"></div>
                            </div>
                        </template>
                    </div>

                    <div class="flex flex-wrap -mx-1">
                        <template x-for="blankday in blankdays">
                            <div style="width: 14.28%" class="text-center border p-1 border-transparent text-sm"></div>
                        </template>
                        <template x-for="(date, dateIndex) in no_of_days" :key="dateIndex">
                            <div style="width: 14.28%" class="px-1 mb-1">
                                <div @click="getDateValue(date)" x-text="date"
                                    class="cursor-pointer text-center text-sm rounded-full leading-loose transition ease-in-out duration-100"
                                    :class="{
                                        'bg-white-100': isToday(date) == true,
                                        'text-gray-600 hover:bg-white-100': isToday(date) == false && isSelectedDate(
                                            date) == false,
                                        'bg-primary-100 text-white-100 hover:bg-opacity-75': isSelectedDate(date) ==
                                            true
                                    }">
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@section('js')
    <script>
        var fechaParts = '{{$fechaInput}}'.split('-');
        var date = new Date(fechaParts[0], fechaParts[1] - 1, fechaParts[2], "00", "00", "00");

        // console.log("date111",date);
        // console.log("date.parse111",Date.parse(date))
        const MONTH_NAMES = [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agostp",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre",
        ];
        const MONTH_SHORT_NAMES = [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
        ];
        const DAYS = ["Dom", "Lun", "Mar", "Mie", "Jue", "Vie", "Sab"];

        function app() {
            return {
                showDatepicker: false,
                datepickerValue: "",
                selectedDate: date,
                dateFormat: "D d M, Y",
                month: "",
                year: "",
                no_of_days: [],
                blankdays: [],
                initDate() {
                    let today;
                    if (this.selectedDate) {
                        today = new Date(Date.parse(this.selectedDate));
                    } else {
                        today = new Date();
                    }
                    this.month = today.getMonth();
                    this.year = today.getFullYear();
                    // console.log("today222",today);
                    // console.log("this.month222",this.month);
                    // console.log("this.year222",this.year);

                    this.datepickerValue = this.formatDateForDisplay(
                        today
                    );
                },
                formatDateForDisplay(date) {
                    let formattedDay = DAYS[date.getDay()];
                    let formattedDate = ("0" + date.getDate()).slice(
                        -2
                    ); // appends 0 (zero) in single digit date
                    let formattedMonth = MONTH_NAMES[date.getMonth()];
                    let formattedMonthShortName =
                        MONTH_SHORT_NAMES[date.getMonth()];
                    let formattedMonthInNumber = (
                        "0" +
                        (parseInt(date.getMonth()) + 1)
                    ).slice(-2);
                    let formattedYear = date.getFullYear();
                    if (this.dateFormat === "DD-MM-YYYY") {
                        return `${formattedDate}-${formattedMonthInNumber}-${formattedYear}`; // 02-04-2021
                    }
                    if (this.dateFormat === "YYYY-MM-DD") {
                        return `${formattedYear}-${formattedMonthInNumber}-${formattedDate}`; // 2021-04-02
                    }
                    if (this.dateFormat === "D d M, Y") {
                        return `${formattedDay} ${formattedDate} ${formattedMonthShortName} ${formattedYear}`; // Tue 02 Mar 2021
                    }
                    // console.log("formatted333",`${formattedDay} ${formattedDate} ${formattedMonth} ${formattedYear}`);
                    return `${formattedDay} ${formattedDate} ${formattedMonth} ${formattedYear}`;
                },
                isSelectedDate(date) {
                    const d = new Date(this.year, this.month, date);
                    // console.log("d4444",d);
                    return this.datepickerValue ===
                        this.formatDateForDisplay(d) ?
                        true :
                        false;
                },
                isToday(date) {
                    const today = new Date();
                    const d = new Date(this.year, this.month, date);
                    // console.log("today555",today);
                    // console.log("d555",d);
                    return today.toDateString() === d.toDateString() ?
                        true :
                        false;
                },
                getDateValue(date) {
                    let selectedDate = new Date(
                        this.year,
                        this.month,
                        date
                    );
                    this.datepickerValue = this.formatDateForDisplay(
                        selectedDate
                    );
                    // this.$refs.date.value = selectedDate.getFullYear() + "-" + ('0' + formattedMonthInNumber).slice(-2) + "-" + ('0' + selectedDate.getDate()).slice(-2);
                    this.isSelectedDate(date);
                    this.showDatepicker = false;

                    // console.log("date66",date);
                    // console.log("selectedDate66",selectedDate);
                    // console.log("this.datepickerValue6",this.datepickerValue);


                    Livewire.emit('fechaSeleccionada', this.formatDate(selectedDate));
                },
                getNoOfDays() {
                    let daysInMonth = new Date(
                        this.year,
                        this.month + 1,
                        0
                    ).getDate();
                    // find where to start calendar day of week
                    let dayOfWeek = new Date(
                        this.year,
                        this.month
                    ).getDay();
                    let blankdaysArray = [];
                    for (var i = 1; i <= dayOfWeek; i++) {
                        blankdaysArray.push(i);
                    }
                    let daysArray = [];
                    for (var i = 1; i <= daysInMonth; i++) {
                        daysArray.push(i);
                    }
                    this.blankdays = blankdaysArray;
                    this.no_of_days = daysArray;
                    // console.log("daysInMonth77",daysInMonth);
                    // console.log("dayOfWeek77",dayOfWeek);
                    // console.log("blankdaysArray77",blankdaysArray);
                    // console.log("daysArray77",daysArray);

                },
                formatDate(date) {
                    var d = new Date(date),
                        month = '' + (d.getMonth() + 1),
                        day = '' + d.getDate(),
                        year = d.getFullYear();

                    if (month.length < 2)
                        month = '0' + month;
                    if (day.length < 2)
                        day = '0' + day;

                    // console.log("date888",date);
                    // console.log("d888",d);
                    // console.log("month888",month);
                    // console.log("day888",day);
                    // console.log("year888",year);
                    return [year, month, day].join('-');
                }
            };
        }
    </script>
@endsection
