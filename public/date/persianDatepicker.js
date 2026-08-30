/* =========================================================
   Persian Date Utilities
========================================================= */

class PersianDate {

    constructor(y,m,d){
        if(y && m && d){
            this.y=y; this.m=m; this.d=d;
        }else{
            const g=new Date();
            const j=PersianDate.toJalali(g.getFullYear(),g.getMonth()+1,g.getDate());
            this.y=j.y; this.m=j.m; this.d=j.d;
        }
    }

    toGregorian(){
        return PersianDate.toGregorian(this.y,this.m,this.d);
    }

    weekday(){
        const g=this.toGregorian();
        return new Date(g.y,g.m-1,g.d).getDay();
    }

    static isLeap(jy){
        const mod=jy%33;
        return [1,5,9,13,17,22,26,30].includes(mod);
    }

    static daysInMonth(y,m){
        if(m<=6) return 31;
        if(m<=11) return 30;
        return PersianDate.isLeap(y)?30:29;
    }

    static toJalali(gy,gm,gd){

        const g_d_m=[0,31,28,31,30,31,30,31,31,30,31,30,31];

        if((gy%4===0 && gy%100!==0) || (gy%400===0)){
            g_d_m[2]=29;
        }

        let gy2=gy-1600;
        let gm2=gm-1;
        let gd2=gd-1;

        let g_day_no=
            365*gy2+
            Math.floor((gy2+3)/4)-
            Math.floor((gy2+99)/100)+
            Math.floor((gy2+399)/400);

        for(let i=1;i<=gm2;i++){
            g_day_no+=g_d_m[i];
        }

        g_day_no+=gd2;

        let j_day_no=g_day_no-79;

        let j_np=Math.floor(j_day_no/12053);
        j_day_no%=12053;

        let jy=979+33*j_np+4*Math.floor(j_day_no/1461);
        j_day_no%=1461;

        if(j_day_no>=366){
            jy+=Math.floor((j_day_no-1)/365);
            j_day_no=(j_day_no-1)%365;
        }

        const jdm=[31,31,31,31,31,31,30,30,30,30,30,29];

        let jm=0;
        for(let i=0;i<11 && j_day_no>=jdm[i];i++){
            j_day_no-=jdm[i];
            jm++;
        }

        let jd=j_day_no+1;

        return {y:jy,m:jm+1,d:jd};
    }

    static toGregorian(jy,jm,jd){

        jy-=979;
        jm-=1;
        jd-=1;

        let j_day_no=
            365*jy+
            Math.floor(jy/33)*8+
            Math.floor(((jy%33)+3)/4);

        let jdm=[31,31,31,31,31,31,30,30,30,30,30,29];

        for(let i=0;i<jm;++i) j_day_no+=jdm[i];
        j_day_no+=jd;

        let g_day_no=j_day_no+79;

        let gy=1600+400*Math.floor(g_day_no/146097);
        g_day_no%=146097;

        let leap=true;

        if(g_day_no>=36525){
            g_day_no--;
            gy+=100*Math.floor(g_day_no/36524);
            g_day_no%=36524;

            if(g_day_no>=365) g_day_no++;
            else leap=false;
        }

        gy+=4*Math.floor(g_day_no/1461);
        g_day_no%=1461;

        if(g_day_no>=366){
            leap=false;
            g_day_no--;
            gy+=Math.floor(g_day_no/365);
            g_day_no%=365;
        }

        let gd=g_day_no+1;

        let gdm=[0,31,leap?29:28,31,30,31,30,31,31,30,31,30,31];

        let gm=0;
        for(let i=1;i<=12;i++){
            if(gd<=gdm[i]){
                gm=i;
                break;
            }
            gd-=gdm[i];
        }

        return {y:gy,m:gm,d:gd};
    }

}


/* =========================================================
   Persian DatePicker
========================================================= */

class PersianDatepicker{

    constructor(input,options={}){

        this.input=input;

        if(input._pdpInit) return;
        input._pdpInit=true;

        const today=new PersianDate();

        this.today={y:today.y,m:today.m,d:today.d};

        this.months=[
            "فروردین","اردیبهشت","خرداد","تیر","مرداد","شهریور",
            "مهر","آبان","آذر","دی","بهمن","اسفند"
        ];

        this.days=["ش","ی","د","س","چ","پ","ج"];

        this.opts=Object.assign({
            format:"YYYY/MM/DD HH:mm",
            onSelect:function(){}
        },options);

        this.current=new PersianDate();

        this.selectedDate=null;
        this.selectedHour="00";
        this.selectedMinute="00";

        this.init();
    }

    init(){

        this.container=document.createElement("div");
        this.container.className="pdp-calendar";
        this.container.style.position="absolute";
        this.container.style.display="none";

        document.body.appendChild(this.container);

        this.input.addEventListener("focus",()=>this.show());
        this.input.addEventListener("click",()=>this.show());

        this.container.addEventListener("click",(e)=>{
            e.stopPropagation();
        });

        document.addEventListener("click",(e)=>{
            if(!this.container.contains(e.target) && e.target!==this.input){
                this.hide();
            }
        });

        this.build();
    }

    show(){

        const rect=this.input.getBoundingClientRect();

        this.container.style.top=(rect.bottom+window.scrollY)+"px";
        this.container.style.left=(rect.left+window.scrollX)+"px";

        this.container.style.display="block";
    }

    hide(){
        this.container.style.display="none";
    }

    build(){

        this.container.innerHTML="";

        const header=document.createElement("div");
        header.className="pdp-header";

        const prev=document.createElement("button");
        prev.innerText="‹";
        prev.onclick=()=>{
            this.current.m--;
            if(this.current.m<1){
                this.current.m=12;
                this.current.y--;
            }
            this.build();
        };

        const next=document.createElement("button");
        next.innerText="›";
        next.onclick=()=>{
            this.current.m++;
            if(this.current.m>12){
                this.current.m=1;
                this.current.y++;
            }
            this.build();
        };

        const yearSel=document.createElement("select");

        for(let y=1300;y<=1500;y++){
            const op=document.createElement("option");
            op.value=y;
            op.innerText=y;
            if(y===this.current.y) op.selected=true;
            yearSel.appendChild(op);
        }

        yearSel.onchange=()=>{
            this.current.y=parseInt(yearSel.value);
            this.build();
        };

        const monthSel=document.createElement("select");

        this.months.forEach((m,i)=>{
            const op=document.createElement("option");
            op.value=i+1;
            op.innerText=m;
            if(i+1===this.current.m) op.selected=true;
            monthSel.appendChild(op);
        });

        monthSel.onchange=()=>{
            this.current.m=parseInt(monthSel.value);
            this.build();
        };

        header.appendChild(prev);
        header.appendChild(yearSel);
        header.appendChild(monthSel);
        header.appendChild(next);

        this.container.appendChild(header);

        const daysRow=document.createElement("div");
        daysRow.className="pdp-days";

        this.days.forEach(d=>{
            const el=document.createElement("span");
            el.innerText=d;
            daysRow.appendChild(el);
        });

        this.container.appendChild(daysRow);

        const grid=document.createElement("div");
        grid.className="pdp-grid";

        const dim=PersianDate.daysInMonth(this.current.y,this.current.m);

        const first=new PersianDate(this.current.y,this.current.m,1);
        let offset=(first.weekday()+1)%7;

        for(let i=0;i<offset;i++){
            const empty=document.createElement("span");
            empty.className="pdp-empty";
            grid.appendChild(empty);
        }

        for(let d=1;d<=dim;d++){

            const cell=document.createElement("span");
            cell.className="pdp-day";
            cell.innerText=d;

            if(
                this.selectedDate &&
                this.selectedDate.y===this.current.y &&
                this.selectedDate.m===this.current.m &&
                this.selectedDate.d===d
            ){
                cell.classList.add("selected");
            }

            if(
                this.today.y===this.current.y &&
                this.today.m===this.current.m &&
                this.today.d===d
            ){
                cell.classList.add("today");
            }

            cell.onclick=()=>{
                this.selectedDate={
                    y:this.current.y,
                    m:this.current.m,
                    d:d
                };
                this.build();
            };

            grid.appendChild(cell);
        }

        this.container.appendChild(grid);

        if(this.opts.format.includes("HH")){

            const timeBox=document.createElement("div");
            timeBox.className="pdp-timebox";

            const hourSel=document.createElement("select");

            for(let h=0;h<24;h++){
                const op=document.createElement("option");
                const hh=h<10?"0"+h:h;
                op.value=hh;
                op.innerText=hh;
                hourSel.appendChild(op);
            }

            hourSel.onchange=()=>this.selectedHour=hourSel.value;

            const minSel=document.createElement("select");

            for(let m=0;m<60;m++){
                const op=document.createElement("option");
                const mm=m<10?"0"+m:m;
                op.value=mm;
                op.innerText=mm;
                minSel.appendChild(op);
            }

            minSel.onchange=()=>this.selectedMinute=minSel.value;

            timeBox.appendChild(minSel);
            timeBox.appendChild(document.createTextNode(":"));
            timeBox.appendChild(hourSel);

            this.container.appendChild(timeBox);

            const btnBox=document.createElement("div");
            btnBox.className="pdp-buttons";

            const cancelBtn=document.createElement("button");
            cancelBtn.innerText="انصراف";
            cancelBtn.onclick=()=>this.hide();

            const okBtn=document.createElement("button");
            okBtn.innerText="تایید";

            okBtn.onclick=()=>{

                if(!this.selectedDate) return;

                const mm=String(this.selectedDate.m).padStart(2,"0");
                const dd=String(this.selectedDate.d).padStart(2,"0");

                const val=
                    `${this.selectedDate.y}/${mm}/${dd} ${this.selectedHour}:${this.selectedMinute}`;

                this.input.value=val;

                this.input.dispatchEvent(new Event("input",{bubbles:true}));
                this.input.dispatchEvent(new Event("change",{bubbles:true}));

                this.opts.onSelect(val);

                this.hide();
            };

            btnBox.appendChild(cancelBtn);
            btnBox.appendChild(okBtn);

            this.container.appendChild(btnBox);
        }

    }

}


/* =========================================================
   jQuery Plugin
========================================================= */

(function($){

    if(!$) return;

    $.fn.persianDatepicker=function(options){

        return this.each(function(){

            if(!this._persianDatepicker){
                this._persianDatepicker=new PersianDatepicker(this,options);
            }

        });

    };

})(window.jQuery);


/* =========================================================
   Filament / Livewire Auto Init
========================================================= */

function initPersianDatepickers(root=document){

    if(typeof $==="undefined") return;

    root.querySelectorAll("input[data-persian-picker]").forEach(function(el){

        if(!el._persianDatepicker){

            $(el).persianDatepicker({
                format:"YYYY/MM/DD HH:mm"
            });

        }

    });

}

document.addEventListener("DOMContentLoaded",()=>{

    initPersianDatepickers();

    const observer=new MutationObserver((mutations)=>{

        mutations.forEach((mutation)=>{

            mutation.addedNodes.forEach((node)=>{

                if(node.nodeType!==1) return;

                initPersianDatepickers(node);

            });

        });

    });

    observer.observe(document.body,{
        childList:true,
        subtree:true
    });

});



