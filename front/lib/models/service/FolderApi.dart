import 'dart:convert';
import 'dart:io';

import 'package:front/models/core/Document.dart';
import 'package:front/models/core/Folder.dart';
import 'package:front/models/core/Rule.dart';
import 'package:front/providers/AuthenticationProvider.dart';
import 'package:dio/dio.dart';
import '/utils/Constants.dart' as constants;

class FolderApi {
  final Dio dio = Dio();
  Future<List<Folder>?> getFolders(
      {required int idOrganization,
      required AuthenticationProvider authentication}) async {
    print('getFolders');
    print('${constants.URL}/folders/$idOrganization');
    Response response;
    try {
      response = await dio.get(
        '${constants.URL}/folders/$idOrganization',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch getFolders $e");
      return null;
    }
    print('responseBody getFolders: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      List<Folder> listFolder = [];
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Iterable iterable = json['list'];
      listFolder = iterable.map((i) => Folder.fromJson(i)).toList();
      // Folder folder = Folder.fromJson(json);

      return listFolder;
    }

    return null;
  }

  Future<Folder?> createFolder(
      {required int idOrganization,
      required Folder folder,
      required AuthenticationProvider authentication}) async {
    print('createFolder');
    print('${constants.URL}/folder/$idOrganization');
    print(folder.name);
    var formJson = jsonEncode(
      <String, dynamic>{
        "name": folder.name,
        "icon": folder.iconIndex,
      },
    );
    print(formJson);
    Response response;
    try {
      response = await dio.post('${constants.URL}/folder/$idOrganization',
          options: Options(
            headers: {'Authorization': "Bearer ${authentication.user.token}"},
            contentType: "application/form-data",
          ),
          data: formJson);
    } catch (e) {
      print("catch createFolders $e");
      return null;
    }
    print('responseBody createFolders: ${response.data}');

    if (response.statusCode == HttpStatus.ok) {
      Map<String, dynamic> json = response.data; //jsonDecode(response.data);
      Folder folder = Folder.fromJson(json);
      // Folder folder = Folder.fromJson(json);

      return folder;
    }

    return null;
  }

  Future<int> deleteFolder({
    required int idFolder,
    required AuthenticationProvider authentication,
  }) async {
    print("deleteFolder");
    print('${constants.URL}/folder/$idFolder');
    Response response;
    try {
      response = await dio.delete(
        '${constants.URL}/folder/$idFolder',
        options: Options(
          headers: {'Authorization': "Bearer ${authentication.user.token}"},
          contentType: "application/form-data",
        ),
      );
    } catch (e) {
      print("catch createFolders $e");
      return HttpStatus.notFound;
    }

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.noContent;
    }

    return HttpStatus.badRequest;
  }

  Future<int> updateFolder({
    required int idFolder,
    required Folder folder,
    required AuthenticationProvider authentication,
  }) async {
    print("updateFolder");
    print('${constants.URL}/folder/$idFolder');
    Response response;
    var formJson = jsonEncode(
      <String, dynamic>{
        "name": folder.name,
        "icon": folder.iconIndex,
      },
    );
    try {
      response = await dio.put('${constants.URL}/folder/$idFolder',
          options: Options(
            headers: {'Authorization': "Bearer ${authentication.user.token}"},
            contentType: "application/form-data",
          ),
          data: formJson);
    } catch (e) {
      print("catch updateFolder $e");
      return HttpStatus.notFound;
    }

    if (response.statusCode == HttpStatus.ok) {
      return HttpStatus.noContent;
    }

    return HttpStatus.badRequest;
  }
}
