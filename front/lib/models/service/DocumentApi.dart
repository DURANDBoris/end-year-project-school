import 'dart:io';

import 'package:front/models/core/Document.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:dio/dio.dart';
import '/utils/Constants.dart' as constants;

class DocumentApi {
  final Dio dio = Dio();
  Future<List<Document>?> getFolderDocument(
      {required int idFolder,
      required AuthenticationProvider authentication}) async {
    print('getFolderDocument');
    print('${constants.URL}/documents/$idFolder');
    Response response;
    try {
      response = await dio.get(
        '${constants.URL}/documents/$idFolder',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch getFolderDocument $e");
      return null;
    }
    print('responseBody getFolderDocument: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      List<Document> listDocument = [];
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Iterable iterable = json['list'];
      listDocument = iterable.map((i) => Document.fromJson(i)).toList();
      // Folder folder = Folder.fromJson(json);

      return listDocument;
    }

    return null;
  }

  Future<int> delDocument(
      {required int idDocument,
      required AuthenticationProvider authentication}) async {
    print('delDocument');
    print('${constants.URL}/document/$idDocument');
    Response response;
    try {
      response = await dio.delete(
        '${constants.URL}/document/$idDocument',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch delDocument $e");
      return HttpStatus.notFound;
    }
    print('responseBody delDocument: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.noContent;
    }

    return HttpStatus.badRequest;
  }
}
