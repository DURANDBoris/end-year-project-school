import 'package:file_picker/file_picker.dart';
import 'package:front/models/core/Document.dart';
import 'package:front/models/core/User.dart';
import 'package:front/models/service/DownloadApi.dart';

class DownloadHelper {
  DownloadApi downloadApi = DownloadApi();
  Future<void> downloadFile(
      {required User user, required List<Document> listDocument}) async {
    String? directory;
    try {
      directory = await FilePicker.platform.getDirectoryPath();
    } catch (e) {
      print("catch $e");
      return;
    }

    if (directory == null) return;
    for (Document document in listDocument) {
      downloadApi.downloadFile(
          user: user, document: document, directory: directory);
    }
  }
}
