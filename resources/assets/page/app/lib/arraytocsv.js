class arraytocsv{

  constructor(args,stockData){

    var data, filename, link;
        var csv = this.convertArrayOfObjectsToCSV({
            data: stockData
        });

        if (csv == null) return;

        filename = args.filename || 'export.csv';

        data = encodeURI(csv);
        //return 

        var pom = document.createElement('a');
        var csvContent=csv; //here we load our csv data 
        var blob = new Blob([csvContent],{type: 'text/csv;charset=utf-8;'});
        var url = URL.createObjectURL(blob);
        pom.href = url;
        pom.setAttribute('download', filename );
        document.body.appendChild(pom);
        pom.click();

  }

  convertArrayOfObjectsToCSV(args){

    var result, ctr, keys, columnDelimiter, lineDelimiter, data;

        data = args.data || null;
        if (data == null || !data.length) {
            return null;
        }

        columnDelimiter = args.columnDelimiter || ',';
        lineDelimiter = args.lineDelimiter || '\n';

        keys = Object.keys(data[0]);

        result = '';
        result += keys.join(columnDelimiter);
        result += lineDelimiter;

        data.forEach(function(item) {
            ctr = 0;
            keys.forEach(function(key) {
                if (ctr > 0) result += columnDelimiter;

                result += item[key];
                ctr++;
            });
            result += lineDelimiter;
        });

        return result;

  }


}

export { arraytocsv as default }

