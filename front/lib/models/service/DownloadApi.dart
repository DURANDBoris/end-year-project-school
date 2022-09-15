import 'dart:io';
import 'package:front/models/core/Document.dart';
import 'package:front/models/core/User.dart';
// import 'package:path_provider/path_provider.dart';

import '/utils/Constants.dart' as constants;
import 'package:dio/dio.dart';

class DownloadApi {
  Future<void> downloadFile(
      {required User user,
      required Document document,
      required String directory}) async {
    // setState(() {
    //   downloading = true;
    // });
    print("download");

    Dio dio = Dio();
    try {
      dio.download(
        '${constants.URL}/download/${document.id}',
        "$directory/${document.name}.pdf",
        options: Options(headers: {
          'accept': 'application/json',
          'Authorization': 'Bearer ${user.token}'
        }),
        onReceiveProgress: (rcv, total) {
          print(
              'received: ${rcv.toStringAsFixed(0)} out of total: ${total.toStringAsFixed(0)}');

          // setState(() {
          //   progress = ((rcv / total) * 100).toStringAsFixed(0);
          // });

          // if (progress == '100') {
          //   setState(() {
          //     isDownloaded = true;
          //   });
          // } else if (double.parse(progress) < 100) {}
        },
        deleteOnError: true,
      ).then((_) {
        // setState(() {
        //   if (progress == '100') {
        //     isDownloaded = true;
        //   }

        //   downloading = false;
        // });
      });
    } catch (e) {
      print("catch download $e");
    }
  }
}






// import 'dart:html' as html;
// import 'dart:typed_data';

// import 'package:http/http.dart' as http;

// import '/utils/Constants.dart' as constants;
// import '../core/User.dart';

// class DownloadApi {
//   void downloadFile(User user) async {
//     http.Response response1 = await http.get(
//       Uri.parse('${constants.URL}/download/1'),
//       headers: {
//         'accept': 'application/json',
//         'Authorization': 'Bearer ${user.token}'
//       },
//     );
//     downLoadFile(response1.bodyBytes);
//   }

//   void downLoadFile(Uint8List res) {
//     final blob = html.Blob([res]);
//     final url = html.Url.createObjectUrlFromBlob(blob);
//     final anchor = html.document.createElement('a') as html.AnchorElement
//       ..href = url
//       ..style.display = 'none'
//       ..download = "salut.pdf";
//     html.document.body!.children.add(anchor);

//     anchor.click();

//     html.document.body!.children.remove(anchor);
//     html.Url.revokeObjectUrl(url);
//   }
// }
